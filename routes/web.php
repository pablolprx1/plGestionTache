<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Route de redirection vers la page de connexion
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Charge les fichiers de routes
require __DIR__.'/projects.php';
require __DIR__.'/users.php';
require __DIR__.'/auth.php';
