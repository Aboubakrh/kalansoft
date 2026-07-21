<?php

namespace App\Livewire\Settings\AnneeScolaires;

use App\Models\Core\AnneeScolaire;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $anneeId;
    public $libelle;
    public $est_active = false;

    protected $rules = [
        'libelle' => 'required|string|max:9|unique:annee_scolaires,libelle',
        'est_active' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['anneeId', 'libelle', 'est_active']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $annee = AnneeScolaire::findOrFail($id);
        $this->anneeId = $annee->id;
        $this->libelle = $annee->libelle;
        $this->est_active = $annee->est_active;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->anneeId) {
            $rules['libelle'] = 'required|string|max:9|unique:annee_scolaires,libelle,' . $this->anneeId;
        }

        $this->validate($rules);

        // Si on active cette année, on désactive les autres
        if ($this->est_active) {
            AnneeScolaire::query()->update(['est_active' => false]);
        }

        AnneeScolaire::updateOrCreate(
            ['id' => $this->anneeId],
            [
                'libelle' => $this->libelle,
                'est_active' => $this->est_active,
            ]
        );

        session()->flash('success', 'Année scolaire enregistrée avec succès.');
        $this->dispatch('close-modal', 'annee-modal');
    }

    public function activate($id)
    {
        AnneeScolaire::query()->update(['est_active' => false]);
        AnneeScolaire::findOrFail($id)->update(['est_active' => true]);
        session()->flash('success', 'L\'année scolaire a été définie comme active.');
    }

    public function delete($id)
    {
        $annee = AnneeScolaire::findOrFail($id);
        if ($annee->est_active) {
            session()->flash('error', 'Impossible de supprimer l\'année scolaire active.');
            return;
        }
        $annee->delete();
        session()->flash('success', 'Année scolaire supprimée avec succès.');
    }

    public function render()
    {
        $annees = AnneeScolaire::where('libelle', 'like', '%' . $this->search . '%')
            ->orderBy('libelle', 'desc')
            ->paginate(10);

        return view('livewire.settings.annee-scolaires.index', compact('annees'))->layout('components.layouts.dashboard');
    }
}
