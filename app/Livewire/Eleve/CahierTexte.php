<?php

declare(strict_types=1);

namespace App\Livewire\Eleve;

use Livewire\Component;
use Livewire\Attributes\Computed;

class CahierTexte extends Component
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
    public function devoirs()
    {
        if (!$this->inscriptionActive) {
            return collect();
        }

        return \App\Models\Pedagogy\CahierTexte::with(['cours.matiere', 'cours.enseignant.user'])
            ->whereHas('cours', function ($q) {
                $q->where('classe_id', $this->inscriptionActive->classe_id);
            })
            ->orderByDesc('date_saisie')
            ->get();
    }

    public function render()
    {
        return view('livewire.eleve.cahier-texte')
            ->layout('components.layouts.dashboard', ['title' => __('Mes Devoirs')]);
    }
}
