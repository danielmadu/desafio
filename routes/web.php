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
        Route::get('/transaction/create', [TransactionsController::class, 'create'])->name('transaction.create');
        Route::post('/transaction/save', [TransactionsController::class, 'save'])->name('transaction.save');
    }
);

require __DIR__.'/auth.php';
