<?php

namespace App\Livewire\Classes;

use Livewire\Component;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Salle;
use App\Models\Pedagogy\Serie;

class Edit extends Component
{
    public Classe $classe;
    public $nom;
    public $niveau;
    public $capacite_maximale;
    public $salle_id;
    public $serie_id;

    public function mount(Classe $classe)
    {
        $this->classe = $classe;
        $this->nom = $classe->nom;
        $this->niveau = $classe->niveau;
        $this->capacite_maximale = $classe->capacite_maximale;
        $this->salle_id = $classe->salle_id;
        $this->serie_id = $classe->serie_id;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:50',
            'niveau' => 'required|string|max:20',
            'capacite_maximale' => 'required|integer|min:1',
            'salle_id' => 'nullable|exists:salles,id',
            'serie_id' => 'nullable|exists:series,id',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->classe->update([
            'nom' => $this->nom,
            'niveau' => $this->niveau,
            'capacite_maximale' => $this->capacite_maximale,
            'salle_id' => $this->salle_id ?: null,
            'serie_id' => $this->serie_id ?: null,
        ]);

        session()->flash('status', 'Classe modifiée avec succès.');
        return $this->redirect(route('admin.classes.index'), navigate: true);
    }

    public function render()
    {
        $salles = Salle::all();
        $series = Serie::all();
        return view('livewire.classes.edit', compact('salles', 'series'))->layout('components.layouts.dashboard');
    }
}
