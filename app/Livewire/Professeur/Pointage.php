<?php

declare(strict_types=1);

namespace App\Livewire\Professeur;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Pointage extends Component
{
    public $personnel;

    public function mount()
    {
        $this->personnel = auth()->user()->personnel;
    }

    #[Computed]
    public function pointages()
    {
        if (! $this->personnel) {
            return collect();
        }

        return DB::table('presence_personnels')
            ->where('personnel_id', $this->personnel->id)
            ->orderByDesc('date')
            ->get();
    }

    #[Computed]
    public function stats()
    {
        $pointages = $this->pointages;
        $totalJours = $pointages->count();
        $joursComplets = $pointages->whereNotNull('heure_arrivee')->whereNotNull('heure_depart')->count();

        return [
            'totalJours' => $totalJours,
            'joursComplets' => $joursComplets,
        ];
    }

    public function render()
    {
        return view('livewire.professeur.pointage')
            ->layout('components.layouts.dashboard', ['title' => __('Mes Pointages & Absences')]);
    }
}
