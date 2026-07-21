<?php

declare(strict_types=1);

namespace App\Livewire\Parent;

use Livewire\Component;

class DossierScolaire extends Component
{
    use Traits\HasEleveSelector;

    public function render()
    {
        return view('livewire.parent.dossier-scolaire')
            ->layout('components.layouts.dashboard', ['title' => __('Dossiers Scolaires')]);
    }
}
