<?php

namespace App\Livewire\Pedagogy\Matieres;

use Livewire\Component;
use App\Models\Pedagogy\Matiere;

class Edit extends Component
{
    public Matiere $matiere;
    public $nom;
    public $code;
    public $couleur;
    public $est_optionnelle;

    public function mount(Matiere $matiere)
    {
        $this->matiere = $matiere;
        $this->nom = $matiere->nom;
        $this->code = $matiere->code;
        $this->couleur = $matiere->couleur;
        $this->est_optionnelle = $matiere->est_optionnelle;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:matieres,code,' . $this->matiere->id,
            'couleur' => 'nullable|string|max:7',
            'est_optionnelle' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->matiere->update([
            'nom' => $this->nom,
            'code' => $this->code,
            'couleur' => $this->couleur,
            'est_optionnelle' => $this->est_optionnelle,
        ]);

        session()->flash('status', 'Matière modifiée avec succès.');
        return $this->redirect(route('admin.matieres.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pedagogy.matieres.edit')->layout('components.layouts.dashboard');
    }
}
