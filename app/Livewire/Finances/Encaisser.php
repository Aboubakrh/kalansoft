<?php

namespace App\Livewire\Finances;

use Livewire\Component;
use App\Models\Pedagogy\Inscription;
use App\Models\Finance\Paiement;
use Illuminate\Support\Facades\DB;

class Encaisser extends Component
{
    public $search = '';
    public $selectedInscription = null;
    
    // Form fields
    public $montant = '';
    public $mode_paiement = 'Espèces';
    public $intitule_tranche = 'Tranche 1';
    public $observation = '';

    #[\Livewire\Attributes\Computed]
    public function inscriptions()
    {
        if (strlen($this->search) > 2) {
            return Inscription::with(['eleve.user', 'classe'])
                ->where(function ($query) {
                    $query->whereHas('eleve.user', function ($q) {
                        $q->where('nom', 'like', '%' . $this->search . '%')
                          ->orWhere('prenom', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('eleve', function ($q) {
                        $q->where('matricule', 'like', '%' . $this->search . '%');
                    });
                })
                ->where('statut', 'Inscrit')
                ->take(5)
                ->get();
        }
        
        return [];
    }

    public function updatedSearch()
    {
        // $this->inscriptions is now computed
    }

    public function selectInscription($id)
    {
        $this->selectedInscription = Inscription::with(['eleve.user', 'classe', 'paiements'])->findOrFail($id);
        $this->search = '';
        // Preset montant to remaining balance if greater than 0
        $this->montant = $this->selectedInscription->soldeRestant() > 0 ? $this->selectedInscription->soldeRestant() : '';
    }

    public function cancelSelection()
    {
        $this->selectedInscription = null;
        $this->reset(['montant', 'mode_paiement', 'intitule_tranche', 'observation']);
    }

    public function save()
    {
        $this->validate([
            'montant' => 'required|numeric|min:1|max:' . ($this->selectedInscription->soldeRestant()),
            'mode_paiement' => 'required|string',
            'intitule_tranche' => 'required|string',
        ]);

        DB::transaction(function () {
            // Generate receipt number REC-YYYYMMDD-XXXX
            $todayCount = Paiement::whereDate('created_at', today())->count() + 1;
            $numero_recu = 'REC-' . now()->format('Ymd') . '-' . str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            Paiement::create([
                'inscription_id' => $this->selectedInscription->id,
                'caissier_id' => auth()->id(),
                'montant' => $this->montant,
                'numero_recu' => $numero_recu,
                'mode_paiement' => $this->mode_paiement,
                'intitule_tranche' => $this->intitule_tranche,
                'observation' => $this->observation,
            ]);
        });

        session()->flash('status', 'Paiement encaissé avec succès.');
        return redirect()->route('admin.finances.paiements');
    }

    public function render()
    {
        return view('livewire.finances.encaisser')
            ->layout('components.layouts.dashboard');
    }
}
