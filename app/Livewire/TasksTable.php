<?php

namespace App\Livewire;

use App\Models\Phase;
use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\On;

class TasksTable extends Component
{
    public $projectId;
    public $editingTaskId = null;
    public $editingTaskTitle;
    public $editingTaskDescription;
    public $newTaskName = [];

    protected $listeners = ['openEditTaskModal', 'updateTaskData'];

    public function render()
    {
        $phases = Phase::where('project_id', $this->projectId)
            ->with(['tasks' => function ($query) {
                $query->orderBy('deadline', 'asc'); // Trier par date d'échéance
            }])
            ->orderBy('order', 'asc')
            ->get();

        return view('livewire.tasks-table', compact('phases'));
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


    public function addTask($phaseId)
    {
        $validatedData = $this->validate([
            'newTaskName.' . $phaseId => 'required|max:255',
        ]);

        Task::create([
            'phase_id' => $phaseId,
            'title' => $this->newTaskName[$phaseId],
            'is_completed' => false,
        ]);

        $this->newTaskName[$phaseId] = '';
    }

    #[On('updateTaskOrder')]
    public function updateTaskOrder($taskIds)
    {
        foreach ($taskIds as $index => $taskId) {
            $task = Task::find($taskId);
            $task->order = $index + 1;
            $task->save();
        }

        $this->render();
    }
}
