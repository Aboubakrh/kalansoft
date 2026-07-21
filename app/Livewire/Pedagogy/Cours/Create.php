<?php

namespace App\Livewire\Pedagogy\Cours;

use Livewire\Component;
use App\Models\Pedagogy\Cours;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Matiere;
use App\Models\Core\Personnel;

class Create extends Component
{
    public $classe_id = '';
    public $matiere_id = '';
    public $enseignant_id = '';
    public $taux_horaire_vacation = 0;

    public function rules()
    {
        return [
            'classe_id' => 'required|exists:classes,id',
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'nullable|exists:personnels,id',
            'taux_horaire_vacation' => 'nullable|numeric|min:0',
        ];
    }

    public function save()
    {
        $this->validate();

        // Check if class already has this subject
        $exists = Cours::where('classe_id', $this->classe_id)
            ->where('matiere_id', $this->matiere_id)
            ->first();

        if ($exists) {
            $this->addError('matiere_id', 'Ce cours existe déjà pour cette classe.');
            return;
        }

        Cours::create([
            'classe_id' => $this->classe_id,
            'matiere_id' => $this->matiere_id,
            'enseignant_id' => $this->enseignant_id ?: null,
            'taux_horaire_vacation' => $this->taux_horaire_vacation ?: 0,
        ]);

        session()->flash('status', 'Cours ajouté avec succès.');
        return $this->redirect(route('admin.cours.index'), navigate: true);
    }

    public function render()
    {
        $classes = Classe::orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')->get();
        $professeurs = Personnel::whereHas('user', function($q) { $q->role('professeur'); })->with('user')->get();

        return view('livewire.pedagogy.cours.create', compact('classes', 'matieres', 'professeurs'))
            ->layout('components.layouts.dashboard');
    }
}
