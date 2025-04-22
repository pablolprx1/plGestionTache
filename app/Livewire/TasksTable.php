<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\On;

class TasksTable extends Component
{
    public $projectId;
    public $editingTaskId = null;
    public $editingTaskTitle;
    public $editingTaskDescription;
    public $newTaskName;

    protected $listeners = ['openEditTaskModal', 'updateTaskData', 'refreshTasksTable'];

    public function render()
    {
        $tasks = Task::where('project_id', $this->projectId)->orderBy('deadline')->get();

        return view('livewire.tasks-table', [
            'tasks' => $tasks,
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
            $this->dispatch('open-edit-task-modal', [
                'title' => $task->title,
                'description' => $task->description,
                'deadline' => $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '',
            ]);
        } else {
            // Gérer le cas où la tâche n'est pas trouvée
            session()->flash('error', 'Tâche non trouvée.');
        }
    }

    public function updateTaskData($title, $description, $deadline)
    {
        $task = Task::find($this->editingTaskId);
        if ($task) {
            $task->title = $title;
            $task->description = $description;
            $task->deadline = $deadline ? \Carbon\Carbon::parse($deadline) : null; // Conversion en date
            $task->save();

            $this->dispatch('close-edit-task-modal');
            $this->reset('editingTaskId');
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
