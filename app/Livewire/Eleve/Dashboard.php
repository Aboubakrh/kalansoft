<?php

declare(strict_types=1);

namespace App\Livewire\Eleve;

use Livewire\Component;
use App\Models\Pedagogy\Note;
use App\Models\Pedagogy\Bulletin;
use App\Models\Pedagogy\EmploiDuTemps as EmploiDuTempsModel;
use App\Models\Pedagogy\CahierTexte;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

class Dashboard extends Component
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
    public function derniereMoyenne()
    {
        if (!$this->inscriptionActive) return null;
        
        return Bulletin::where('inscription_id', $this->inscriptionActive->id)
            ->orderByDesc('trimestre')
            ->first();
    }

    #[Computed]
    public function prochainDevoir()
    {
        if (!$this->inscriptionActive) return null;

        return CahierTexte::whereHas('cours', fn($q) => $q->where('classe_id', $this->inscriptionActive->classe_id))
            ->whereNotNull('date_remise')
            ->whereDate('date_remise', '>=', Carbon::today())
            ->orderBy('date_remise', 'asc')
            ->first();
    }

    #[Computed]
    public function dernieresNotes()
    {
        if (!$this->eleve) return collect();

        return Note::with(['evaluation.cours.matiere'])
            ->where('eleve_id', $this->eleve->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function emploiDuTempsJour()
    {
        if (!$this->inscriptionActive) return collect();

        // 1 = Lundi, 7 = Dimanche
        $jourSemaineNum = Carbon::now()->dayOfWeekIso;
        $jours = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
        $jourSemaineStr = $jours[$jourSemaineNum] ?? 'Lundi';

        return EmploiDuTempsModel::with(['cours.matiere', 'cours.enseignant.user', 'salle'])
            ->where('classe_id', $this->inscriptionActive->classe_id)
            ->where('jour', $jourSemaineStr)
            ->orderBy('heure_debut')
            ->get();
    }

    public function render()
    {
        return view('livewire.eleve.dashboard')
            ->layout('components.layouts.dashboard', ['title' => __('Espace Élève')]);
    }
}
