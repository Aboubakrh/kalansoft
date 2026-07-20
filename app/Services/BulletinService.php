<?php

namespace App\Services;

use App\Models\Pedagogy\Inscription;
use App\Models\Pedagogy\Bulletin;
use App\Models\Pedagogy\Note;
use App\Models\Pedagogy\Cours;
use App\Models\Pedagogy\Evaluation;
use App\Models\Pedagogy\Coefficient;
use Illuminate\Support\Facades\DB;

class BulletinService
{
    /**
     * Calcule et génère les données du bulletin pour un élève (inscription) à une période donnée.
     */
    public function generateBulletinData($inscription_id, $periode)
    {
        $inscription = Inscription::with(['eleve.user', 'classe.serie'])->findOrFail($inscription_id);
        $classe = $inscription->classe;
        $serie_id = $classe->serie_id ?? null;

        // Récupérer tous les cours de la classe
        $coursList = Cours::with(['matiere', 'enseignant.user'])->where('classe_id', $classe->id)->get();

        $lignesBulletin = [];
        $sommeMoyennesPonderees = 0;
        $sommeCoefficients = 0;

        foreach ($coursList as $cours) {
            $moyenneCours = $this->calculateMoyenneCours($inscription->eleve_id, $cours->id, $periode);
            
            if ($moyenneCours !== null) {
                $coeff = 1;
                if ($serie_id) {
                    $coefficientRecord = Coefficient::where('serie_id', $serie_id)
                        ->where('matiere_id', $cours->matiere_id)
                        ->first();
                    if ($coefficientRecord) {
                        $coeff = $coefficientRecord->valeur;
                    }
                }

                $sommeMoyennesPonderees += ($moyenneCours * $coeff);
                $sommeCoefficients += $coeff;

                $lignesBulletin[] = [
                    'matiere' => $cours->matiere->nom,
                    'moyenne' => round($moyenneCours, 2),
                    'coefficient' => $coeff,
                    'moyenne_ponderee' => round($moyenneCours * $coeff, 2),
                    'professeur' => $cours->enseignant->user->name ?? 'N/A'
                ];
            }
        }

        $moyenneGenerale = 0;
        if ($sommeCoefficients > 0) {
            $moyenneGenerale = round($sommeMoyennesPonderees / $sommeCoefficients, 2);
        }

        $trimestre = $this->parseTrimestre($periode);

        $bulletin = Bulletin::updateOrCreate(
            ['inscription_id' => $inscription_id, 'trimestre' => $trimestre],
            [
                'moyenne' => $moyenneGenerale,
                'date_generation' => now()
            ]
        );

        $this->updateRanks($classe->id, $trimestre);
        $bulletin->refresh();

        return [
            'inscription' => $inscription,
            'periode' => $periode,
            'lignes' => collect($lignesBulletin)->sortBy('matiere')->values()->all(),
            'moyenne_generale' => $moyenneGenerale,
            'rang' => $bulletin->rang,
            'total_coefficients' => $sommeCoefficients,
            'appreciation_globale' => $this->getAppreciation($moyenneGenerale)
        ];
    }

    private function calculateMoyenneCours($eleve_id, $cours_id, $periode)
    {
        $evaluations = Evaluation::where('cours_id', $cours_id)
            ->where('periode', $periode)
            ->get();

        if ($evaluations->isEmpty()) {
            return null;
        }

        $sommePonderee = 0;
        $totalCoeffs = 0;

        foreach ($evaluations as $eval) {
            $note = Note::where('evaluation_id', $eval->id)
                ->where('eleve_id', $eleve_id)
                ->first();

            if ($note && $note->valeur !== null) {
                // Ramener la note sur 20
                $noteSur20 = ($note->valeur / $eval->bareme) * 20;
                $coeff = $eval->coefficient ?? 1;

                $sommePonderee += ($noteSur20 * $coeff);
                $totalCoeffs += $coeff;
            }
        }

        if ($totalCoeffs == 0) {
            return null;
        }

        return $sommePonderee / $totalCoeffs;
    }

    private function updateRanks($classe_id, $trimestre)
    {
        $bulletins = Bulletin::whereHas('inscription', function($q) use ($classe_id) {
            $q->where('classe_id', $classe_id);
        })->where('trimestre', $trimestre)->orderByDesc('moyenne')->get();

        $rang = 1;
        $precedenteMoyenne = null;
        $vraiRang = 1;

        foreach ($bulletins as $b) {
            if ($precedenteMoyenne !== null && $b->moyenne < $precedenteMoyenne) {
                $rang = $vraiRang;
            }
            $b->update(['rang' => $rang]);
            
            $precedenteMoyenne = $b->moyenne;
            $vraiRang++;
        }
    }

    private function getAppreciation($moyenne)
    {
        if ($moyenne >= 16) return 'Très bien';
        if ($moyenne >= 14) return 'Bien';
        if ($moyenne >= 12) return 'Assez bien';
        if ($moyenne >= 10) return 'Passable';
        if ($moyenne >= 8) return 'Insuffisant';
        return 'Faible';
    }

    private function parseTrimestre($periode)
    {
        if (str_contains(strtolower($periode), '1')) return 1;
        if (str_contains(strtolower($periode), '2')) return 2;
        if (str_contains(strtolower($periode), '3')) return 3;
        return 1; // Default
    }
}
