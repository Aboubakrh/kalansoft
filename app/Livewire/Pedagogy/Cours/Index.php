<?php

namespace App\Livewire\Pedagogy\Cours;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedagogy\Cours;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Cours::findOrFail($id)->delete();
        session()->flash('status', 'Cours supprimé.');
    }

    public function render()
    {
        $cours = Cours::with(['classe', 'matiere', 'enseignant.user'])
            ->whereHas('classe', function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('matiere', function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.pedagogy.cours.index', compact('cours'))
            ->layout('components.layouts.dashboard');
    }
}
