<?php

use App\Http\Controllers\API\BalanceController;
use Illuminate\Support\Facades\Route;

# Начисление средств
Route::post('/deposit', [BalanceController::class, 'deposit'])->name('api.balance.deposit');

# Списание средств
Route::post('/withdraw', [BalanceController::class, 'withdraw'])->name('api.balance.withdraw');

# Перевод между пользователями
Route::post('/transfer', [BalanceController::class, 'transfer'])->name('api.balance.transfer');

# Получение баланса
Route::get('/balance/{user_id}', [BalanceController::class, 'get'])->name('api.balance.get');
