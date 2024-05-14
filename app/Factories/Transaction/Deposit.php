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

class Deposit implements TransactionInterface
{
  public function save(TransactionRequest $request): Transaction
  {
    try {
      $transaction = new Transaction();
      $user = Auth::user();
      $data = [
        ...$request->validated(),
        'user_id' => $user->id,
        'date' => Carbon::today()->toDateString(),
      ];
      $transaction->fill($data)->save();

      $user->update([
        'balance' => $user->balance + $request->validated('amount')
      ]);

      return $transaction;
    } catch (\Exception $e) {
      throw new InternalErrorException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
  }
}