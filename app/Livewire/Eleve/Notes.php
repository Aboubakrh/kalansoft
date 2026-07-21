<?php

declare(strict_types=1);

namespace App\Livewire\Eleve;

use Livewire\Component;
use App\Models\Pedagogy\Note;
use App\Models\Pedagogy\Evaluation;
use Livewire\Attributes\Computed;

class Notes extends Component
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
    public function notes()
    {
        if (!$this->eleve) {
            return collect();
        }

        return Note::with(['evaluation.cours.matiere'])
            ->where('eleve_id', $this->eleve->id)
            ->orderByDesc('created_at')
            ->get();
    }

    #[Computed]
    public function periodes()
    {
        if (!$this->inscriptionActive) {
            return collect();
        }

        return Evaluation::whereHas('cours', function ($q) {
                $q->where('classe_id', $this->inscriptionActive->classe_id);
            })
            ->select('periode')
            ->distinct()
            ->pluck('periode');
    }

    public function render()
    {
        return view('livewire.eleve.notes')
            ->layout('components.layouts.dashboard', ['title' => __('Mes Notes')]);
    }
}
