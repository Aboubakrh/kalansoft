<?php

namespace App\Livewire\Eleves;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Core\Eleve;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $eleves = Eleve::with('user')
            ->whereHas('user', function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('prenom', 'like', '%' . $this->search . '%');
            })
            ->orWhere('matricule', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.eleves.index', compact('eleves'))
            ->layout('components.layouts.dashboard');
    }
}
