<?php

namespace App\Livewire\Classes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Salle;

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
        Classe::findOrFail($id)->delete();
        session()->flash('status', 'Classe supprimée avec succès.');
    }

    public function render()
    {
        $classes = Classe::with('salle')
            ->where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('niveau', 'like', '%' . $this->search . '%')
            ->paginate(10);
            
        $salles = Salle::all();
        $series = \App\Models\Pedagogy\Serie::all();

        return view('livewire.classes.index', compact('classes', 'salles', 'series'))
            ->layout('components.layouts.dashboard');
    }
}
