<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'fee',
        'date'
    ];

    public function scopeTransactionType(Builder $query, string $transactionType): Builder
    {
        $table = $this->getTable();

        return $query->where($table . '.transaction_type', $transactionType);
    }
}