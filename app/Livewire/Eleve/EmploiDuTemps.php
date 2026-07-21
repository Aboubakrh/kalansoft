<?php

declare(strict_types=1);

namespace App\Livewire\Eleve;

use Livewire\Component;
use App\Models\Pedagogy\EmploiDuTemps as EmploiDuTempsModel;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

class EmploiDuTemps extends Component
{
    public $eleve;
    public $inscriptionActive;

    public function mount()
    {
        $this->eleve = auth()->user()->eleve;
        if ($this->eleve) {
            $this->inscriptionActive = $this->eleve->inscriptions()
                ->whereHas('anneeScolaire', fn($q) => $q->where('est_active', true))
                ->first();
        }
    }

    #[Computed]
    public function seances()
    {
        if (!$this->inscriptionActive || !$this->inscriptionActive->classe_id) {
            return collect();
        }

        return EmploiDuTempsModel::with(['cours.matiere', 'cours.enseignant.user', 'salle'])
            ->where('classe_id', $this->inscriptionActive->classe_id)
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get();
    }

    public function getJoursSemaine()
    {
        return [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
    }

    public function render()
    {
        return view('livewire.eleve.emploi-du-temps')
            ->layout('components.layouts.dashboard', ['title' => __('Mon Emploi du Temps')]);
    }
}
