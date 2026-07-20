<?php

namespace App\Livewire\Professeurs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Core\Personnel;

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
        $personnel = Personnel::findOrFail($id);
        if ($personnel->user) {
            $personnel->user->delete();
        }
        $personnel->delete();
        session()->flash('status', 'Professeur supprimé avec succès.');
    }

    public function render()
    {
        $professeurs = Personnel::whereHas('user', function($q) {
                $q->role('professeur');
            })
            ->where(function($q) {
                $q->whereHas('user', function($u) {
                    $u->where('nom', 'like', '%' . $this->search . '%')
                      ->orWhere('prenom', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('matricule', 'like', '%' . $this->search . '%');
            })
            ->with('user')
            ->paginate(10);

        return view('livewire.professeurs.index', compact('professeurs'))
            ->layout('components.layouts.dashboard');
    }
}
