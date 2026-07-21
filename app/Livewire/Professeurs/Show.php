<?php

namespace App\Livewire\Professeurs;

use Livewire\Component;
use App\Models\Core\Personnel;
use App\Models\Pedagogy\Cours;

class Show extends Component
{
    public Personnel $personnel;

    public function mount(Personnel $personnel)
    {
        $this->personnel = $personnel;
    }

    public function render()
    {
        $cours = Cours::where('enseignant_id', $this->personnel->id)
            ->with(['classe', 'matiere'])
            ->get();

        return view('livewire.professeurs.show', compact('cours'))
            ->layout('components.layouts.dashboard');
    }
}
