<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    public function create()
    {
        return view('projects.form', ['project' => null]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $validatedData['user_id'] = Auth::id();

        try {
            $project = Project::create($validatedData);
            return redirect()->route('projects.index')->with('success', 'Projet créé avec succès.');
        } catch (QueryException $e) {
            // Ajoutez l'ID au message d'erreur
            $errorMessage = "Erreur lors de la création du projet avec l'utilisateur ID : " . Auth::id() . ". Détails : " . $e->getMessage();
            return back()->withErrors(['message' => $errorMessage]);
        }
    }

    public function edit(Project $project)
    {
        if (!$project->isCreator(Auth::user())) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce projet.');
        }

        return view('projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $project->update($validatedData);

        return redirect()->route('projects.index')->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Project $project)
    {
        if (!$project->isCreator(Auth::user())) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce projet.');
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé avec succès.');
    }

    public function show(Project $project)
    {
        $this->authorizeProjectAccess($project);

        // Charger les tâches associées au projet
        $tasks = $project->tasks()->orderBy('deadline')->get();

        // Retourner la vue avec les données du projet et des tâches
        return view('projects.show', compact('project', 'tasks'));
    }

    public function changeStatus(Request $request, Project $project)
    {
        $this->authorizeProjectAccess($project);

        // Changer le statut du projet
        $project->update([
            'is_completed' => !$project->is_completed,
        ]);

        // Rediriger avec un message de succès
        $statusMessage = $project->is_completed ? 'Le projet a été marqué comme terminé.' : 'Le projet a été marqué comme non terminé.';
        return redirect()->route('projects.edit', $project)->with('success', $statusMessage . ' Les modifications ont été enregistrées.');
    }

    protected function authorizeProjectAccess(Project $project)
    {
        $user = Auth::user();

        // Vérifie si l'utilisateur est le créateur ou un collaborateur du projet
        if ($project->user_id !== $user->id && !$project->users->contains($user)) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à ce projet.');
        }
    }
}
