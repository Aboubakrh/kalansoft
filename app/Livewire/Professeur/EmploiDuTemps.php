<?php

namespace App\Livewire\Professeur;

use Livewire\Component;
use App\Models\Pedagogy\EmploiDuTemps as EmploiDuTempsModel;
use App\Models\Pedagogy\Classe;

class EmploiDuTemps extends Component
{
    public $classe_id = '';

    public function render()
    {
        $personnel_id = auth()->user()->personnel->id ?? null;
        
        $query = EmploiDuTempsModel::with(['cours.matiere', 'classe', 'salle'])
            ->whereHas('cours', function($q) use ($personnel_id) {
                $q->where('enseignant_id', $personnel_id);
            });

        if (!empty($this->classe_id)) {
            $query->where('classe_id', $this->classe_id);
        }

        $emplois = $query->get();

        // Pour le filtre (facultatif)
        $classesIds = $emplois->pluck('classe_id')->unique();
        $classes = Classe::whereIn('id', $classesIds)->get();

        return view('livewire.professeur.emploi-du-temps', [
            'emplois' => $emplois,
            'classes' => $classes,
        ])->layout('components.layouts.dashboard');
    }
}
