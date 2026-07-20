<?php

namespace App\Livewire\Professeur;

use Livewire\Component;
use App\Models\Pedagogy\EmploiDuTemps;
use App\Models\Pedagogy\CahierTexte;
use App\Models\Pedagogy\Evaluation;
use App\Models\Pedagogy\Cours;
use App\Models\Core\Notification;
use App\Models\Pedagogy\Presence;
use Illuminate\Support\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $personnel = auth()->user()->personnel;
        
        if (!$personnel) {
            abort(403, 'Accès non autorisé.');
        }

        $coursProf = Cours::where('personnel_id', $personnel->id)->pluck('id');
        
        // 1. Heures de cours de la semaine
        $seancesSemaine = EmploiDuTemps::whereIn('cours_id', $coursProf)->get();
        $heuresSemaine = 0;
        foreach ($seancesSemaine as $seance) {
            if ($seance->heure_debut && $seance->heure_fin) {
                $debut = Carbon::parse($seance->heure_debut);
                $fin = Carbon::parse($seance->heure_fin);
                $heuresSemaine += $debut->diffInHours($fin) ?: 1;
            }
        }

        // 2. Evaluations en attente
        $evaluationsAttente = Evaluation::whereIn('cours_id', $coursProf)
            ->where('date_evaluation', '<=', now())
            ->doesntHave('notes')
            ->count();

        // 3. Taux de présence moyen (placeholder 0% as requested for now)
        $tauxPresence = 0; 
        
        // 4. Emploi du temps du jour
        $jourAujourdhui = $this->getJourSemaine(now()->dayOfWeek);
        $emploiDuTempsJour = EmploiDuTemps::with(['cours.matiere', 'classe', 'salle'])
            ->whereIn('cours_id', $coursProf)
            ->where('jour', $jourAujourdhui)
            ->orderBy('heure_debut')
            ->get();

        // 5. Cahier de texte - Dernières saisies
        $cahiersTexte = CahierTexte::with(['cours.matiere', 'classe'])
            ->whereIn('cours_id', $coursProf)
            ->orderBy('date_saisie', 'desc')
            ->take(5)
            ->get();

        // 6. Classes principales
        $classes = Cours::with('classe')
            ->where('personnel_id', $personnel->id)
            ->get()
            ->unique('classe_id')
            ->map(function ($cours) {
                return $cours->classe;
            })->filter();

        // 7. Notifications
        $notifications = Notification::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->latest()
            ->take(3)
            ->get();

        return view('livewire.professeur.dashboard', [
            'heuresSemaine' => $heuresSemaine,
            'evaluationsAttente' => $evaluationsAttente,
            'tauxPresence' => $tauxPresence,
            'emploiDuTempsJour' => $emploiDuTempsJour,
            'cahiersTexte' => $cahiersTexte,
            'classes' => $classes,
            'notifications' => $notifications,
        ])->layout('components.layouts.dashboard', ['title' => 'Espace Pédagogique - Professeur']);
    }

    private function getJourSemaine($dayOfWeek)
    {
        $jours = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];
        return $jours[$dayOfWeek] ?? 'Lundi';
    }
}
