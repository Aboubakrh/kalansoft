<?php

declare(strict_types=1);

namespace App\Livewire\Eleve;

use Livewire\Component;
use App\Models\Pedagogy\Presence;
use Livewire\Attributes\Computed;

class Absences extends Component
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
    public function absences()
    {
        if (!$this->eleve) {
            return collect();
        }

        return Presence::with(['cours.matiere', 'cours.enseignant.user'])
            ->whereHas('eleves', function ($q) {
                $q->where('eleves.id', $this->eleve->id)
                  ->whereIn('presence_eleves.statut', ['Absent', 'Retard']);
            })
            // We load the specific pivot for this student to display it
            ->with(['eleves' => function ($q) {
                $q->where('eleves.id', $this->eleve->id);
            }])
            ->orderByDesc('date_appel')
            ->get();
    }

    public function render()
    {
        return view('livewire.eleve.absences')
            ->layout('components.layouts.dashboard', ['title' => __('Mes Absences')]);
    }
}
