<?php

use App\Livewire\Messages;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::get('/', Messages::class)->name('chat');
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
