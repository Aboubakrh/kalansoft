<?php

namespace App\Livewire\Salles;

use Livewire\Component;
use App\Models\Pedagogy\Salle;

class Edit extends Component
{
    public Salle $salle;
    public $nom;
    public $capacite;
    public $batiment;

    public function mount(Salle $salle)
    {
        $this->salle = $salle;
        $this->nom = $salle->nom;
        $this->capacite = $salle->capacite;
        $this->batiment = $salle->batiment;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:50',
            'capacite' => 'required|integer|min:1',
            'batiment' => 'nullable|string|max:100',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->salle->update([
            'nom' => $this->nom,
            'capacite' => $this->capacite,
            'batiment' => $this->batiment,
        ]);

        session()->flash('status', 'Salle modifiée avec succès.');
        return $this->redirect(route('admin.salles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.salles.edit')->layout('components.layouts.dashboard');
    }
}
