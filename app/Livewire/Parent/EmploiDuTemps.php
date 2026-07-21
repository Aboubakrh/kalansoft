<?php

declare(strict_types=1);

namespace App\Livewire\Parent;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Core\ParentEleve;
use App\Models\Core\Eleve;
use App\Models\Pedagogy\Inscription;
use App\Models\Pedagogy\EmploiDuTemps as EmploiDuTempsModel;

class EmploiDuTemps extends Component
{
    use Traits\HasEleveSelector;

    #[Computed]
    public function emplois()
    {
        $inscription = $this->currentInscription;
        if (!$inscription || !$inscription->classe_id) {
            return collect();
        }

        return EmploiDuTempsModel::with(['cours.matiere', 'cours.enseignant.user', 'salle'])
            ->where('classe_id', $inscription->classe_id)
            ->get();
    }

    public function render()
    {
        return view('livewire.parent.emploi-du-temps')
            ->layout('components.layouts.dashboard', ['title' => __('Emploi du Temps')]);
    }
}
