<?php

namespace App\Livewire\Pedagogy\Matieres;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedagogy\Matiere;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Form fields
    public $matiereId;
    public $code = '';
    public $couleur = '#3B82F6'; // Default blue
    public $est_optionnelle = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Matiere::findOrFail($id)->delete();
        session()->flash('status', 'Matière supprimée.');
    }

    public function render()
    {
        $matieres = Matiere::where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.pedagogy.matieres.index', compact('matieres'))
            ->layout('components.layouts.dashboard');
    }
}
