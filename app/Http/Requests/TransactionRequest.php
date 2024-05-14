<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionRequest extends FormRequest
{
  private $fee = 0;

  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'transaction_type' => ['required', Rule::in(config('common.transactionType'))],
      'amount' => [
        'required',
        'decimal:0,2',
        function ($attributeName, $amount, $fail) {
          if ($this->post('transaction_type') == config('common.transactionType.withdrawal')) {
            $this->setWithdrawalfee($amount);
            $user = Auth::user();
            if (($amount + $this->fee) > $user->balance) {
              $fail("Amount must be less then " . ($amount + $this->fee));
            }
          }
        }
      ],
    ];
  }


  public function getFee()
  {
    return $this->fee;
  }

  private function setWithdrawalfee($amount)
  {
    $user = Auth::user();
    switch ($user->account_type) {
      case config('common.accountType.individual'):
        $this->fee = $this->calculateIndividualAccountFee($amount);
        break;
      case config('common.accountType.business'):
        $this->fee = $this->calculateBusinessAccountFee($amount);
        break;
      default:
        $this->fee = 0;
        break;
    }
  }

  private function calculateIndividualAccountFee($amount)
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
    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
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