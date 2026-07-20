<?php

namespace App\Livewire\Salles;

use Livewire\Component;
use App\Models\Pedagogy\Salle;

class Create extends Component
{
    public $nom = '';
    public $capacite = '';
    public $batiment = '';

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

        Salle::create([
            'nom' => $this->nom,
            'capacite' => $this->capacite,
            'batiment' => $this->batiment,
        ]);

        session()->flash('status', 'Salle ajoutée avec succès.');
        return $this->redirect(route('admin.salles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.salles.create')->layout('components.layouts.dashboard');
    }
}
