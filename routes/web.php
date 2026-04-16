<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/fast', [HomeController::class, 'fast'])->name('fast');
Route::get('/slow', [HomeController::class, 'slow'])->name('slow');
Route::get('/slow-request', [HomeController::class, 'slowRequest'])->name('slow-request');
Route::get('/slow-outgoing', [HomeController::class, 'slow_outgoing'])->name('slow-outgoing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
