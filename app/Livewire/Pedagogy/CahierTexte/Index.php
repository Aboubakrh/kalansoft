<?php

namespace App\Livewire\Pedagogy\CahierTexte;

use Livewire\Component;
use App\Models\Pedagogy\CahierTexte as CahierTexteModel;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Matiere;

class Index extends Component
{
    public $classe_id = '';
    public $matiere_id = '';

    public function render()
    {
        $classes = Classe::all();
        $matieres = Matiere::all();

        $query = CahierTexteModel::with(['cours.matiere', 'cours.classe', 'cours.enseignant.user']);

        if (!empty($this->classe_id)) {
            $query->whereHas('cours', function($q) {
                $q->where('classe_id', $this->classe_id);
            });
        }
        
        if (!empty($this->matiere_id)) {
            $query->whereHas('cours', function($q) {
                $q->where('matiere_id', $this->matiere_id);
            });
        }

        $cahiers = $query->orderByDesc('date')->get();

        return view('livewire.pedagogy.cahier-texte.index', [
            'classes' => $classes,
            'matieres' => $matieres,
            'cahiers' => $cahiers,
        ])->layout('components.layouts.dashboard');
    }
}
