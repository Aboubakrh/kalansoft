<?php

namespace App\Livewire\Eleves;

use Livewire\Component;
use App\Models\Core\User;
use App\Models\Core\Eleve;
use App\Models\Core\ParentEleve;
use App\Models\Core\AnneeScolaire;
use App\Models\Pedagogy\Classe;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    // Infos Élève
    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $telephone = '';
    public $matricule = '';
    public $date_naissance = '';
    public $lieu_naissance = '';
    public $sexe = 'M';
    
    // Infos Tuteur (Parent)
    public $parent_nom = '';
    public $parent_prenom = '';
    public $parent_email = '';
    public $parent_telephone = '';
    public $parent_profession = '';
    public $parent_adresse = '';
    
    // Inscription
    public $classe_id = '';
    
    public function save()
    {
        $this->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'nullable|email|unique:users,email',
            'matricule' => 'required|string|unique:eleves,matricule',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string',
            'sexe' => 'required|in:M,F',
            'parent_nom' => 'required|string',
            'parent_prenom' => 'required|string',
            'parent_telephone' => 'required|string',
            'classe_id' => 'required|exists:classes,id',
        ]);

        DB::transaction(function () {
            // 1. Créer le User de l'Élève
            $userEleve = User::create([
                'nom' => $this->nom,
                'prenom' => $this->prenom,
                'email' => $this->email ?: strtolower($this->prenom . '.' . $this->nom . rand(10,99) . '@kalansoft.com'),
                'telephone' => $this->telephone,
                'password' => Hash::make('password'), // Mot de passe par défaut
            ]);
            $userEleve->assignRole('eleve');

            // 2. Créer l'Élève
            $eleve = $userEleve->eleve()->create([
                'matricule' => $this->matricule,
                'date_naissance' => $this->date_naissance,
                'lieu_naissance' => $this->lieu_naissance,
                'sexe' => $this->sexe,
                'nom_tuteur' => $this->parent_prenom . ' ' . $this->parent_nom,
                'telephone_tuteur' => $this->parent_telephone,
            ]);

            // 3. Créer ou trouver le Parent
            // Simplification: on crée un nouveau parent
            $userParent = User::firstOrCreate(
                ['email' => $this->parent_email ?: strtolower('parent.' . $this->parent_nom . '@kalansoft.com')],
                [
                    'nom' => $this->parent_nom,
                    'prenom' => $this->parent_prenom,
                    'telephone' => $this->parent_telephone,
                    'password' => Hash::make('password'),
                ]
            );
            $userParent->assignRole('parent');

            $parentEleve = $userParent->parentEleve()->firstOrCreate([
                'profession' => $this->parent_profession,
                'adresse' => $this->parent_adresse,
            ]);

            // Attacher le parent à l'élève
            $eleve->parents()->attach($parentEleve->id, ['lien_parente' => 'Père/Mère']);

            // 4. Inscription
            $anneeScolaire = AnneeScolaire::where('statut', 'En cours')->first() ?? AnneeScolaire::first();
            if ($anneeScolaire) {
                $eleve->inscriptions()->create([
                    'classe_id' => $this->classe_id,
                    'annee_scolaire_id' => $anneeScolaire->id,
                    'frais_scolarite' => 150000, // Valeur par défaut ou depuis la classe
                    'statut' => 'Inscrit'
                ]);
                session()->flash('status', 'Élève inscrit avec succès.');
            } else {
                session()->flash('status', 'Élève créé avec succès. IMPORTANT: L\'inscription dans la classe a été ignorée car aucune Année Scolaire n\'est configurée dans le système.');
            }
        });

        return redirect()->route('admin.eleves.index');
    }

    public function render()
    {
        $classes = Classe::all();
        return view('livewire.eleves.create', compact('classes'))
            ->layout('components.layouts.dashboard');
    }
}
