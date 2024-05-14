<?php

declare(strict_types=1);

namespace App\Services;

use App\Factories\TransactionFactory;
use App\Http\Requests\TransactionRequest;

class TransactionService
{
  public function save(TransactionRequest $request): void
  {
    $instance = TransactionFactory::createInstance(array_flip(config('common.transactionType'))[$request->validated('transaction_type')] ?? '');

    $instance->save($request);
  }
}
