<?php

namespace App\Livewire\Salles;

use Livewire\Component;
use Livewire\WithPagination;
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
        Salle::findOrFail($id)->delete();
        session()->flash('status', 'Salle supprimée avec succès.');
    }

    public function render()
    {
        $salles = Salle::where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('batiment', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.salles.index', compact('salles'))
            ->layout('components.layouts.dashboard');
    }
}
