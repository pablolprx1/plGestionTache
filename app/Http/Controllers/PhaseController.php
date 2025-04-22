<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    public function index($projectId)
    {
        $phases = Phase::where('project_id', $projectId)->orderBy('order')->get();
        return response()->json($phases); // Retourne les phases au format JSON
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $phase = Phase::create([
            'name' => $request->name,
            'project_id' => $projectId,
        ]);

        return redirect()->back()->with('success', 'Phase ajoutée avec succès.');
    }

    public function update(Request $request, Phase $phase)
    {
        $request->validate(['name' => 'required|max:255']);

        $phase->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'Phase mise à jour avec succès.');
    }

    public function destroy(Phase $phase)
    {
        $phase->tasks()->delete(); // Supprimer les tâches associées
        $phase->delete(); // Supprimer la phase

        return redirect()->back()->with('success', 'Phase supprimée avec succès.');
    }
}
