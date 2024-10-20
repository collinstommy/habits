<?php

use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(middleware: ['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Volt::route('/habit/{habit}', 'pages.habit-page')->name('habit.show');

Route::get('/habit/{id}', [HabitController::class, 'show'])->name('habit.show');

require __DIR__.'/auth.php';
