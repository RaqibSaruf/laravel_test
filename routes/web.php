<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')
    ->namespace('App\Http\Controllers')
    ->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('transaction.list');
        Route::prefix('deposit')
            ->group(function () {
                Route::get('/', [TransactionController::class, 'deposit'])->name('transaction.deposit.list');
                Route::get('/add', [TransactionController::class, 'addDeposit'])->name('transaction.deposit.add');
            });
        Route::prefix('withdrawal')
            ->group(function () {
                Route::get('/', [TransactionController::class, 'withdrawal'])->name('transaction.withdrawal.list');
                Route::get('/add', [TransactionController::class, 'addWithdrawal'])->name('transaction.withdrawal.add');
            });
        Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
    });


require __DIR__ . '/auth.php';