<?php

declare(strict_types=1);

namespace App\Livewire\Professeur;

use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Cours;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Classes extends Component
{
    public $personnel;

    public $selectedClasseId = null;

    public function mount()
    {
        $this->personnel = auth()->user()->personnel;
    }

    #[Computed]
    public function classes()
    {
        if (! $this->personnel) {
            return collect();
        }

        $classeIds = Cours::where('enseignant_id', $this->personnel->id)
            ->pluck('classe_id')
            ->unique();

        return Classe::with(['serie', 'inscriptions' => function ($q) {
            $q->whereHas('anneeScolaire', fn ($a) => $a->where('est_active', true));
        }])->whereIn('id', $classeIds)->get();
    }

    public function selectClasse($classeId)
    {
        $this->selectedClasseId = $classeId;
    }

    #[Computed]
    public function selectedClasse()
    {
        if (! $this->selectedClasseId) {
            return $this->classes->first();
        }

        return $this->classes->firstWhere('id', $this->selectedClasseId);
    }

    #[Computed]
    public function eleves()
    {
        $classe = $this->selectedClasse;
        if (! $classe) {
            return collect();
        }

        return $classe->inscriptions()
            ->whereHas('anneeScolaire', fn ($q) => $q->where('est_active', true))
            ->with(['eleve.user'])
            ->get()
            ->pluck('eleve');
    }

    #[Computed]
    public function mesCours()
    {
        $classe = $this->selectedClasse;
        if (! $classe || ! $this->personnel) {
            return collect();
        }

        return Cours::with('matiere')
            ->where('enseignant_id', $this->personnel->id)
            ->where('classe_id', $classe->id)
            ->get();
    }

    public function render()
    {
        return view('livewire.professeur.classes')
            ->layout('components.layouts.dashboard', ['title' => __('Mes Classes')]);
    }
}
