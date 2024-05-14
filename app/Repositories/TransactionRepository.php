<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionRepository implements Repository
{
  public function paginate(array $filters = [])
  {
    $query = Transaction::where('user_id', Auth::id());

    if (isset($filters['transaction_type'])) {
      $query->transactionType($filters['transaction_type']);
    }

    return $query->latest()
      ->paginate($filters['limit'] ?? config('common.paginationLimit'));
  }
}