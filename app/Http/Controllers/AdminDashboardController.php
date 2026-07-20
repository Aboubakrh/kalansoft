<?php

namespace App\Http\Controllers;

use App\Models\Core\Eleve;
use App\Models\Core\Personnel;
use App\Models\Finance\Paiement;
use App\Models\Pedagogy\Inscription;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalEleves = Eleve::count();
        $totalProfesseurs = Personnel::where('type_personnel', 'Enseignant')->count();
        $scolariteEncaissee = Paiement::sum('montant');
        
        // Un taux de présence statique pour le moment
        $tauxPresence = 94;

        // Récupérer les 5 dernières inscriptions avec les relations
        $recentInscriptions = Inscription::with(['eleve.user', 'classe'])
            ->latest('date_inscription')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEleves',
            'totalProfesseurs',
            'scolariteEncaissee',
            'tauxPresence',
            'recentInscriptions'
        ));
    }
}
