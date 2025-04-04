<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
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

        $projects = Project::query();

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

    public function show(Project $project)
    {
        $phases = $project->phases()->with('tasks')->orderBy('order')->get(); // Trier les phases par ordre
        return view('projects.show', compact('project', 'phases'));
    }

    public function edit(Project $project)
    {
        return view('projects.form', compact('project')); // Passe le projet existant à la vue
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
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé avec succès.');
    }

}
