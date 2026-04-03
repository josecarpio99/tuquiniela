<?php

declare(strict_types=1);

use App\Http\Controllers\BalanceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('balance/history', [BalanceController::class, 'history'])->name('balance.history');
});

require __DIR__.'/settings.php';
