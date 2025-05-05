<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Permet de gérer les collaborateurs d'un projet
class ProjectMemberController extends Controller
{
    // Ajoute un utilisateur au projet
    public function addUser(Request $request, Project $project)
    {
        // Vérifie que l'utilisateur actuel est le créateur du projet
        if (!$project->isCreator(Auth::user())) {
            return redirect()->back()->with('error', 'Seul le créateur du projet peut ajouter des utilisateurs.');
        }

        $request->validate([
            'username' => 'required|string|exists:users,username'
        ]);

        $user = User::where('username', $request->username)->first();

        // Vérifie si l'utilisateur est déjà membre du projet
        if ($project->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }

        // Ajoute l'utilisateur au projet
        $project->users()->attach($user->id);

        return redirect()->back()->with('success', 'Utilisateur ajouté avec succès.');
    }

    // Supprime un utilisateur du projet
    public function removeUser(Project $project, User $user)
    {
        // Vérifie que l'utilisateur actuel est le créateur du projet
        if (!$project->isCreator(Auth::user())) {
            return redirect()->back()->with('error', 'Seul le créateur du projet peut supprimer des utilisateurs.');
        }

        // Supprime l'utilisateur du projet
        $project->users()->detach($user->id);

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
