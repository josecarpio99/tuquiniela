<?php

declare(strict_types=1);

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuinielaController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('quinielas', [QuinielaController::class, 'index'])->name('quinielas.index');
Route::get('quinielas/{quiniela}', [QuinielaController::class, 'show'])->name('quinielas.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('balance/history', [BalanceController::class, 'history'])->name('balance.history');

    Route::post('quinielas/{quiniela}/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::livewire('tickets/{ticket}/predictions', 'pages::tickets.predictions')->name('tickets.predictions');
});

require __DIR__.'/settings.php';
