<?php

namespace App\Livewire\Pedagogy\Matieres;

use Livewire\Component;
use App\Models\Pedagogy\Matiere;

class Create extends Component
{
    public $nom = '';
    public $code = '';
    public $couleur = '#CBD5E1';
    public $est_optionnelle = false;

    public function rules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:matieres,code',
            'couleur' => 'nullable|string|max:7',
            'est_optionnelle' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        Matiere::create([
            'nom' => $this->nom,
            'code' => $this->code,
            'couleur' => $this->couleur,
            'est_optionnelle' => $this->est_optionnelle,
        ]);

        session()->flash('status', 'Matière ajoutée avec succès.');
        return $this->redirect(route('admin.matieres.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pedagogy.matieres.create')->layout('components.layouts.dashboard');
    }
}
