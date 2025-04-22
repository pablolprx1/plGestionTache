<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use Illuminate\Support\Facades\Route;

// Projets : CRUD complet
Route::resource('projects', ProjectController::class)->middleware(['auth']); // CRUD complet pour les projets

// Gestion des collaborateurs
Route::post('/projects/{project}/users', [ProjectMemberController::class, 'addUser'])->name('projects.users.add'); // Ajoute un utilisateur à un projet
Route::delete('/projects/{project}/users/{user}', [ProjectMemberController::class, 'removeUser'])->name('projects.users.remove'); // Supprime un utilisateur d'un projet
Route::post('/projects/{project}/complete', [ProjectController::class, 'changeStatus'])->name('projects.changeStatus'); // Complete ou incomplète un projet
