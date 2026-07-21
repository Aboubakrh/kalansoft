<?php

declare(strict_types=1);

namespace App\Livewire\Parent\Traits;

use Livewire\Attributes\Computed;
use App\Models\Core\ParentEleve;
use App\Models\Core\Eleve;
use App\Models\Pedagogy\Inscription;

trait HasEleveSelector
{
    public ?int $selectedEleveId = null;

    public function mountHasEleveSelector(): void
    {
        $eleves = $this->eleves;
        if ($eleves->isNotEmpty()) {
            $this->selectedEleveId = $eleves->first()->id;
        }
    }

    #[Computed]
    public function parentEleve(): ?ParentEleve
    {
        return auth()->user()->parentEleve;
    }

    #[Computed]
    public function eleves()
    {
        if (! $this->parentEleve()) {
            return collect();
        }

        return $this->parentEleve()->eleves()->with(['user', 'documents', 'inscriptions' => function ($query) {
            $query->whereHas('anneeScolaire', function ($q) {
                $q->where('est_active', true);
            })->with(['classe', 'bulletins', 'paiements']);
        }])->get();
    }

    #[Computed]
    public function selectedEleve(): ?Eleve
    {
        if (! $this->selectedEleveId) {
            return null;
        }

        return $this->eleves->firstWhere('id', $this->selectedEleveId);
    }

    #[Computed]
    public function currentInscription(): ?Inscription
    {
        if (! $this->selectedEleve) {
            return null;
        }

        return $this->selectedEleve->inscriptions->first();
    }

    public function selectEleve(int $eleveId): void
    {
        $this->selectedEleveId = $eleveId;
    }
}
