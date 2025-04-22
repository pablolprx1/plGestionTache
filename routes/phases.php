<?php

use App\Http\Controllers\PhaseController;
use Illuminate\Support\Facades\Route;

// Phases
Route::get('/projects/{project}/phases', [PhaseController::class, 'index'])->name('phases.index');
Route::post('/projects/{project}/phases', [PhaseController::class, 'store'])->name('phases.store');
Route::put('/phases/{phase}', [PhaseController::class, 'update'])->name('phases.update');
Route::delete('/phases/{phase}', [PhaseController::class, 'destroy'])->name('phases.destroy');
