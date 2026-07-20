<?php

namespace App\Livewire\Pedagogy\Bulletins;

use Livewire\Component;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Inscription;

class Index extends Component
{
    public $classe_id = '';
    public $periode = 'Trimestre 1';

    public function render()
    {
        $classes = Classe::all();
        $inscriptions = [];

        if (!empty($this->classe_id)) {
            $inscriptions = Inscription::with('eleve.user')
                ->where('classe_id', $this->classe_id)
                ->where('statut', 'Inscrit')
                ->get()
                ->sortBy('eleve.user.nom');
        }

        return view('livewire.pedagogy.bulletins.index', [
            'classes' => $classes,
            'inscriptions' => $inscriptions
        ])->layout('components.layouts.dashboard');
    }

    public function generateBulletin($inscription_id)
    {
        return redirect()->route('admin.bulletins.download', [
            'inscription' => $inscription_id,
            'periode' => $this->periode
        ]);
    }
}
