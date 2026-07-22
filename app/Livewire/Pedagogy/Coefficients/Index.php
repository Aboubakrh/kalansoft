<?php

declare(strict_types=1);

namespace App\Livewire\Pedagogy\Coefficients;

use App\Models\Pedagogy\Matiere;
use App\Models\Pedagogy\Serie;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    // Holds matrix values: $coefficients[$matiereId][$serieId] = int
    public $coefficients = [];

    public function mount()
    {
        $this->loadCoefficients();
    }

    public function loadCoefficients()
    {
        $rows = DB::table('coefficients')->get();
        $matrix = [];

        foreach ($rows as $row) {
            $matrix[$row->matiere_id][$row->serie_id] = $row->valeur;
        }

        $this->coefficients = $matrix;
    }

    public function updateCoefficient($matiereId, $serieId, $valeur)
    {
        $valeur = is_numeric($valeur) ? (int) $valeur : 0;

        if ($valeur <= 0) {
            DB::table('coefficients')
                ->where('matiere_id', $matiereId)
                ->where('serie_id', $serieId)
                ->delete();
            unset($this->coefficients[$matiereId][$serieId]);
        } else {
            DB::table('coefficients')->updateOrInsert(
                ['matiere_id' => $matiereId, 'serie_id' => $serieId],
                ['valeur' => $valeur, 'updated_at' => now(), 'created_at' => now()]
            );
            $this->coefficients[$matiereId][$serieId] = $valeur;
        }

        session()->flash('status', 'Coefficient mis à jour avec succès.');
    }

    #[Computed]
    public function matieres()
    {
        return Matiere::orderBy('nom')->get();
    }

    #[Computed]
    public function series()
    {
        return Serie::orderBy('nom')->get();
    }

    public function render()
    {
        return view('livewire.pedagogy.coefficients.index')
            ->layout('components.layouts.dashboard', ['title' => __('Matrice des Coefficients')]);
    }
}
