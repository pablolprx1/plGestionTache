<?php

namespace App\Livewire;

use Livewire\Component;

class ManagePhases extends Component
{
    public $showModal = false;

    protected $listeners = ['openManagePhasesModal' => 'openModal', 'closeManagePhasesModal' => 'closeModal'];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.manage-phases');
    }
}
