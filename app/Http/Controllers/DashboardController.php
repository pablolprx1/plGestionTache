<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }
        $userId = $user->id; // ID de l'utilisateur connecté

        // Tâches terminées
        $completedTasks = Task::where('assigned_user_id', $userId)
            ->where('is_completed', true)
            ->get();

        // Tâches en retard
        $overdueTasks = Task::where('assigned_user_id', $userId)
            ->where('is_completed', false)
            ->where('deadline', '<', Carbon::now())
            ->get();

        // Tâches à venir
        $upcomingTasks = Task::where('assigned_user_id', $userId)
            ->where('is_completed', false)
            ->where('deadline', '>=', Carbon::now())
            ->get();

        return view('dashboard', compact('completedTasks', 'overdueTasks', 'upcomingTasks'));
    }
}
