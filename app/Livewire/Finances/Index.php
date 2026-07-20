<?php

namespace App\Livewire\Finances;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Finance\Paiement;

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
        $paiements = Paiement::with(['inscription.eleve.user', 'inscription.classe', 'caissier'])
            ->whereHas('inscription.eleve.user', function($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                  ->orWhere('prenom', 'like', '%' . $this->search . '%');
            })
            ->orWhere('numero_recu', 'like', '%' . $this->search . '%')
            ->latest('date_paiement')
            ->paginate(10);

        return view('livewire.finances.index', compact('paiements'))
            ->layout('components.layouts.dashboard');
    }
}
