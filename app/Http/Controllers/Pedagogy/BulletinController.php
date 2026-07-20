<?php

namespace App\Http\Controllers\Pedagogy;

use App\Http\Controllers\Controller;
use App\Services\BulletinService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Core\Parametre;

class BulletinController extends Controller
{
    public function download($inscription_id, $periode)
    {
        $service = new BulletinService();
        $data = $service->generateBulletinData($inscription_id, $periode);

        $parametre = Parametre::first();
        $ecoleName = $parametre ? $parametre->nom_ecole : 'KalanSoft';
        
        // On fusionne avec les données générales pour la vue
        $viewData = array_merge($data, [
            'ecoleName' => $ecoleName,
        ]);

        $pdf = Pdf::loadView('pdf.bulletin', $viewData);

        return $pdf->download('bulletin_' . $data['inscription']->eleve->matricule . '_' . $periode . '.pdf');
    }
}
