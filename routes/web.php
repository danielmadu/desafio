<?php

use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dashboard')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'show'])->name('dashboard');
        Route::get('/transactions', [TransactionsController::class, 'list'])->name('transactions.list');
        Route::get('/transactions/create', [TransactionsController::class, 'create'])->name('transactions.create');
        Route::post('/transactions/save', [TransactionsController::class, 'save'])->name('transactions.save');
        Route::get('/transactions/sended', [TransactionsController::class, 'create'])->name('transactions.sended');
        Route::get('/transactions/received', [TransactionsController::class, 'received'])->name('transactions.received');
    }
);

require __DIR__.'/auth.php';
