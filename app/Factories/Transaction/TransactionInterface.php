<?php

declare(strict_types=1);

namespace App\Factories\Transaction;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;

interface TransactionInterface
{
  public function save(TransactionRequest $request): Transaction;
}
