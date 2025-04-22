<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TasksTable extends Component
{
    public $projectId;
    public $filterUserId = null;
    public $editingTaskId = null;
    public $editingTaskTitle;
    public $editingTaskDescription;
    public $editingTaskDeadline;
    public $editingTaskAssignedUserId;
    public $newTaskName;

    protected $listeners = ['openEditTaskModal', 'updateTaskData', 'refreshTasksTable'];


    public function render()
    {
        $tasksQuery = Task::where('project_id', $this->projectId);

        // Filtrer par utilisateur assigné
        if ($this->filterUserId) {
            $tasksQuery->where('assigned_user_id', $this->filterUserId);
        }
        $tasks = $tasksQuery->orderBy('deadline')->get();
        $project = \App\Models\Project::find($this->projectId);

        // Récupération des utilisateurs liés au projet
        if ($project) {
            $mainUser = DB::table('users')
                ->where('id', $project->user_id)
                ->select('users.id', 'users.name', 'users.email')
                ->first();

            $collaborators = DB::table('project_user')
                ->join('users', 'project_user.user_id', '=', 'users.id')
                ->where('project_user.project_id', $this->projectId)
                ->select('users.id', 'users.name', 'users.email')
                ->get();

            $projectUsers = collect([$mainUser])->merge($collaborators)->unique('id');
        } else {
            $projectUsers = collect();
        }

        return view('livewire.tasks-table', [
            'tasks' => $tasks,
            'projectUsers' => $projectUsers,
        ]);
    }

    public function toggleTaskState($taskId)
    {
        $task = Task::find($taskId);
        $task->is_completed = !$task->is_completed;
        $task->save();
    }

    public function deleteTask($taskId)
    {
        Task::find($taskId)->delete();
    }

    public function openEditTaskModal($taskId)
    {
        $task = Task::find($taskId);

        if ($task) {
            $this->editingTaskId = $taskId;
            $this->editingTaskTitle = $task->title;
            $this->editingTaskDescription = $task->description;
            $this->editingTaskDeadline = $task->deadline ? $task->deadline->format('Y-m-d') : null;
            $this->editingTaskAssignedUserId = $task->assigned_user_id;

            $project = \App\Models\Project::find($this->projectId);

            if ($project) {
                $mainUser = DB::table('users')
                    ->where('id', $project->user_id)
                    ->select('users.id', 'users.name', 'users.email')
                    ->first();

                $collaborators = DB::table('project_user')
                    ->join('users', 'project_user.user_id', '=', 'users.id')
                    ->where('project_user.project_id', $this->projectId)
                    ->select('users.id', 'users.name', 'users.email')
                    ->get();

                $projectUsers = collect([$mainUser])->merge($collaborators)->unique('id');
            } else {
                $projectUsers = collect();
            }

            $this->dispatch('open-edit-task-modal', [
                'title' => $task->title,
                'description' => $task->description,
                'deadline' => $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '',
                'assignedUserId' => $task->assigned_user_id,
                'projectUsers' => $projectUsers,
            ]);
        } else {
            session()->flash('error', 'Tâche non trouvée.');
        }
    }

    public function updateTaskData($title, $description, $deadline, $assignedUserId)
    {
        $this->validate([
            'editingTaskTitle' => 'required|max:255',
            'editingTaskDescription' => 'nullable|max:1000',
            'editingTaskDeadline' => 'nullable|date',
            'editingTaskAssignedUserId' => 'nullable|exists:users,id',
        ]);

        $task = Task::find($this->editingTaskId);

        if ($task) {
            $task->title = $title;
            $task->description = $description;
            $task->deadline = $deadline ? \Carbon\Carbon::parse($deadline) : null;
            $task->assigned_user_id = $assignedUserId ? $assignedUserId : null; // Assigner l'utilisateur ou le laisser vide
            $task->save();

            $this->dispatch('close-edit-task-modal');
            $this->reset('editingTaskId', 'editingTaskTitle', 'editingTaskDescription', 'editingTaskDeadline', 'editingTaskAssignedUserId');
        } else {
            session()->flash('error', 'Tâche non trouvée.');
        }
    }

    public function addTask()
    {
        $this->validate([
            'newTaskName' => 'required|max:255',
        ]);

        Task::create([
            'project_id' => $this->projectId,
            'title' => $this->newTaskName,
            'is_completed' => false,
        ]);

        $this->newTaskName = ''; // Réinitialiser le champ après l'ajout
        $this->dispatch('refreshTasksTable');
        }

    public function refreshTasksTable()
    {
        // Cette méthode sera appelée pour rafraîchir le composant
        $this->render();
    }

}
