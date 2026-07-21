<?php

declare(strict_types=1);

namespace App\Livewire\Parent;

use Livewire\Component;
use App\Models\Core\AnneeScolaire;
use Livewire\Attributes\Computed;
use App\Models\Core\ParentEleve;
use App\Models\Core\Eleve;
use App\Models\Pedagogy\Inscription;

class Dashboard extends Component
{
    use Traits\HasEleveSelector;

    public function render()
    {
        return view('parent.dashboard')
            ->layout('components.layouts.dashboard', ['title' => __('Espace Famille')]);
    }
}
