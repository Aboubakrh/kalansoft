<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de notes - {{ $inscription->eleve->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2d3748;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0 0 0;
            font-size: 16px;
            color: #4a5568;
        }
        .student-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .student-info td {
            padding: 5px;
        }
        .student-info .label {
            font-weight: bold;
            width: 120px;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .grades-table th, .grades-table td {
            border: 1px solid #cbd5e0;
            padding: 8px;
            text-align: center;
        }
        .grades-table th {
            background-color: #f7fafc;
            font-weight: bold;
            text-transform: uppercase;
        }
        .grades-table td.text-left {
            text-align: left;
        }
        .summary-box {
            width: 100%;
            border: 2px solid #2d3748;
            padding: 15px;
            margin-bottom: 30px;
            box-sizing: border-box;
        }
        .summary-box table {
            width: 100%;
        }
        .summary-box td {
            padding: 5px;
            font-size: 14px;
        }
        .signatures {
            width: 100%;
            margin-top: 50px;
        }
        .signatures td {
            text-align: center;
            width: 50%;
            font-weight: bold;
        }
        .signatures .sign-box {
            height: 100px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $ecoleName }}</h1>
        <h3>BULLETIN DE NOTES - {{ strtoupper($periode) }}</h3>
    </div>

    <table class="student-info">
        <tr>
            <td class="label">Nom de l'élève :</td>
            <td><strong>{{ $inscription->eleve->user->nom }} {{ $inscription->eleve->user->prenom }}</strong></td>
            <td class="label">Matricule :</td>
            <td>{{ $inscription->eleve->matricule }}</td>
        </tr>
        <tr>
            <td class="label">Classe :</td>
            <td>{{ $inscription->classe->nom }}</td>
            <td class="label">Série :</td>
            <td>{{ $inscription->classe->serie->nom ?? 'Générale' }}</td>
        </tr>
        <tr>
            <td class="label">Année scolaire :</td>
            <td>{{ $inscription->anneeScolaire->libelle ?? '2026-2027' }}</td>
            <td class="label"></td>
            <td></td>
        </tr>
    </table>

    <table class="grades-table">
        <thead>
            <tr>
                <th class="text-left">Matière</th>
                <th>Professeur</th>
                <th>Moyenne (/20)</th>
                <th>Coefficient</th>
                <th>Moy. Pondérée</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lignes as $ligne)
                <tr>
                    <td class="text-left"><strong>{{ $ligne['matiere'] }}</strong></td>
                    <td>{{ $ligne['professeur'] }}</td>
                    <td>{{ number_format($ligne['moyenne'], 2, ',', ' ') }}</td>
                    <td>{{ $ligne['coefficient'] }}</td>
                    <td>{{ number_format($ligne['moyenne_ponderee'], 2, ',', ' ') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucune note enregistrée pour cette période.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #edf2f7; font-weight: bold;">
                <td colspan="3" class="text-left">TOTAL</td>
                <td>{{ $total_coefficients }}</td>
                <td>{{ number_format(collect($lignes)->sum('moyenne_ponderee'), 2, ',', ' ') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <table>
            <tr>
                <td><strong>Moyenne Générale :</strong></td>
                <td style="font-size: 18px;"><strong>{{ number_format($moyenne_generale, 2, ',', ' ') }} / 20</strong></td>
                <td><strong>Rang :</strong></td>
                <td style="font-size: 18px;"><strong>{{ $rang }}{{ $rang == 1 ? 'er' : 'ème' }}</strong></td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 15px;">
                    <strong>Appréciation Globale :</strong> {{ $appreciation_globale }}
                </td>
            </tr>
        </table>
    </div>

    <table class="signatures">
        <tr>
            <td>Le Titulaire / Professeur Principal</td>
            <td>Le Directeur des Études</td>
        </tr>
        <tr>
            <td class="sign-box"></td>
            <td class="sign-box"></td>
        </tr>
    </table>

</body>
</html>
