<?php

declare(strict_types=1);

namespace App\Factories\Transaction;

use App\Factories\Transaction\TransactionInterface;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class Withdrawal implements TransactionInterface
{
  public function save(TransactionRequest $request): Transaction
  {
    try {
      $transaction = new Transaction();
      $user = Auth::user();
      $amount = $request->validated('amount');
      $fee =  $this->getWithdrawalfee($user->account_type, $amount);
      $data = [
        ...$request->validated(),
        'user_id' => $user->id,
        'date' => date('Y-m-d'),
        'fee' => $fee
      ];

      $transaction->fill($data)->save();

      $user->update([
        'balance' => $user->balance - $amount - $fee
      ]);

      return $transaction;
    } catch (\Exception $e) {
      throw new InternalErrorException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }

  private function getWithdrawalfee($accountType, $amount)
  {
    switch ($accountType) {
      case config('common.accountType.individual'):
        return $this->calculateIndividualaccountFee($amount);
      case config('common.accountType.business'):
        return $this->calculateBusinessAccountFee($amount);
      default:
        return 0;
    }
  }

  private function calculateIndividualaccountFee($amount)
  {
    $remainingFreeWithdrawal = $this->fetchRemainingFreeWithdrawalOfCurrentMonth();
    $isFriday = Carbon::now()->isFriday();
    if ($isFriday) {
      $chargedAmount = 0;
    } else if ($remainingFreeWithdrawal > 1000) {
      $chargedAmount = $amount - $remainingFreeWithdrawal;
    } else {
      $chargedAmount = $amount - 1000;
    }
    return $chargedAmount * (0.015 / 100);
  }

  private function calculateBusinessAccountFee($amount)
  {
    $totalWithdrawal = Transaction::where('user_id', Auth::id())
      ->sum('amount');

    if ($totalWithdrawal >= 50000) {
      return $amount * (0.015 / 100);
    } else {
      return $amount * (0.025 / 100);
    }
  }

  private function fetchRemainingFreeWithdrawalOfCurrentMonth()
  {
    // Get the first day of the current month
    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

    // Get the last day of the current month
    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

    // Fetch data from the table where the date column is within the current month
    $currentMonthWithdrawal = Transaction::where('user_id', Auth::id())
      ->whereDate('date', '>=', $startOfMonth)
      ->whereDate('date', '<=', $endOfMonth)
      ->sum('amount');

    if ($currentMonthWithdrawal < 5000) {
      return 5000 - $currentMonthWithdrawal;
    }

    return 0;
  }
}