<?php

namespace App\Livewire\Finances\FraisScolaires;

use App\Models\Core\AnneeScolaire;
use App\Models\Finance\FraisScolarite;
use App\Models\Pedagogy\Classe;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $fraisId;
    public $classe_id;
    public $annee_scolaire_id;
    public $montant;

    public function mount()
    {
        $activeYear = AnneeScolaire::where('est_active', true)->first();
        if ($activeYear) {
            $this->annee_scolaire_id = $activeYear->id;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['fraisId', 'classe_id', 'montant']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $frais = FraisScolarite::findOrFail($id);
        $this->fraisId = $frais->id;
        $this->classe_id = $frais->classe_id;
        $this->annee_scolaire_id = $frais->annee_scolaire_id;
        $this->montant = $frais->montant;
    }

    public function save()
    {
        $this->validate([
            'classe_id' => 'required|exists:classes,id',
            'annee_scolaire_id' => 'required|exists:annee_scolaires,id',
            'montant' => 'required|numeric|min:0',
        ]);

        // Check for uniqueness
        $exists = FraisScolarite::where('classe_id', $this->classe_id)
            ->where('annee_scolaire_id', $this->annee_scolaire_id)
            ->when($this->fraisId, function ($query) {
                $query->where('id', '!=', $this->fraisId);
            })->exists();

        if ($exists) {
            $this->addError('classe_id', 'Les frais pour cette classe et cette année scolaire existent déjà.');
            return;
        }

        FraisScolarite::updateOrCreate(
            ['id' => $this->fraisId],
            [
                'classe_id' => $this->classe_id,
                'annee_scolaire_id' => $this->annee_scolaire_id,
                'montant' => $this->montant,
            ]
        );

        session()->flash('success', 'Frais de scolarité enregistrés avec succès.');
        $this->dispatch('close-modal', 'frais-modal');
    }

    public function delete($id)
    {
        FraisScolarite::findOrFail($id)->delete();
        session()->flash('success', 'Frais supprimés avec succès.');
    }

    public function render()
    {
        $frais = FraisScolarite::with(['classe', 'anneeScolaire'])
            ->whereHas('classe', function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('anneeScolaire', function ($query) {
                $query->where('libelle', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $classes = Classe::orderBy('nom')->get();
        $annees = AnneeScolaire::orderBy('libelle', 'desc')->get();

        return view('livewire.finances.frais-scolaires.index', compact('frais', 'classes', 'annees'))
            ->layout('components.layouts.dashboard');
    }
}
