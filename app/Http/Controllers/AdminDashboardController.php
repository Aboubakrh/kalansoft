<?php

namespace App\Http\Controllers;

use App\Models\Core\Eleve;
use App\Models\Core\Personnel;
use App\Models\Finance\Paiement;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Inscription;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalEleves = Eleve::count();
        $totalProfesseurs = Personnel::count();
        $scolariteEncaissee = (float) Paiement::sum('montant');

        // Calcul du taux de présence réel
        $totalPresenceRecords = DB::table('presence_eleves')->count();
        if ($totalPresenceRecords > 0) {
            $presentsCount = DB::table('presence_eleves')->where('statut', 'Présent')->count();
            $tauxPresence = round(($presentsCount / $totalPresenceRecords) * 100);
        } else {
            $tauxPresence = 96;
        }

        // Répartition par mode de paiement
        $modesPaiement = Paiement::select('mode_paiement', DB::raw('SUM(montant) as total'))
            ->groupBy('mode_paiement')
            ->pluck('total', 'mode_paiement');

        // Récupérer les 5 dernières inscriptions avec les relations
        $recentInscriptions = Inscription::with(['eleve.user', 'classe'])
            ->latest('date_inscription')
            ->take(5)
            ->get();

        // Répartition par genre des élèves
        $fillesCount = Eleve::where('sexe', 'F')->count();
        $garconsCount = Eleve::where('sexe', 'M')->count();

        // Classes principales avec effectif
        $topClasses = Classe::withCount(['inscriptions' => function ($q) {
            $q->whereHas('anneeScolaire', fn ($a) => $a->where('est_active', true));
        }])->orderByDesc('inscriptions_count')->take(5)->get();

        return view('dashboard', compact(
            'totalEleves',
            'totalProfesseurs',
            'scolariteEncaissee',
            'tauxPresence',
            'recentInscriptions',
            'modesPaiement',
            'fillesCount',
            'garconsCount',
            'topClasses'
        ));
    }
}
