<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMemberController extends Controller
{
    // Ajouter un utilisateur au projet
    public function addUser(Request $request, Project $project)
    {
        // Vérifier que l'utilisateur actuel est le créateur du projet
        if (!$project->isCreator(Auth::user())) {
            return redirect()->back()->with('error', 'Seul le créateur du projet peut ajouter des utilisateurs.');
        }

        $request->validate([
            'username' => 'required|string|exists:users,username'
        ]);

        $user = User::where('username', $request->username)->first();

        // Vérifier si l'utilisateur est déjà membre du projet
        if ($project->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }

        // Ajouter l'utilisateur au projet
        $project->users()->attach($user->id);

        return redirect()->back()->with('success', 'Utilisateur ajouté avec succès.');
    }

    // Supprimer un utilisateur du projet
    public function removeUser(Project $project, User $user)
    {
        // Vérifier que l'utilisateur actuel est le créateur du projet
        if (!$project->isCreator(Auth::user())) {
            return redirect()->back()->with('error', 'Seul le créateur du projet peut supprimer des utilisateurs.');
        }

        // Supprimer l'utilisateur du projet
        $project->users()->detach($user->id);

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $user = Auth::user();

        // Filtrer les projets où l'utilisateur est créateur ou collaborateur
        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        // Appliquer les filtres de statut
        if ($status === 'completed') {
            $projects->completed();
        } elseif ($status === 'incomplete') {
            $projects->incomplete();
        }

        $projects = $projects->paginate(10);

        return view('projects.index', compact('projects', 'status'));
    }
}
