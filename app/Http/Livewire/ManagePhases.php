<?php

namespace App\Http\Livewire;

use App\Models\Phase;
use Livewire\Component;

class ManagePhases extends Component
{
    public $projectId;
    public $showModal = false;
    public $editingPhaseId;
    public $editingPhaseName;
    public $newPhaseName;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
    }

    public function getPhasesProperty()
    {
        return Phase::where('project_id', $this->projectId)->orderBy('order')->get();
    }

    public function render()
    {
        return view('livewire.manage-phases');
    }
}
