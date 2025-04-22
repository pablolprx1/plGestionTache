<?php

use Illuminate\Support\Facades\Route;

// Route de redirection vers la page de connexion
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Charge les fichiers de routes
require __DIR__.'/projects.php';
require __DIR__.'/users.php';
require __DIR__.'/auth.php';
