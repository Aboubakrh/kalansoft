<?php

namespace App\Livewire\Professeurs;

use Livewire\Component;
use App\Models\Core\User;
use App\Models\Core\Personnel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Create extends Component
{
    public $nom = '';
    public $prenom = '';
    public $email = '';
    public $matricule = '';
    public $date_embauche = '';
    public $statut = 'Actif';
    public $password = '';

    public function rules()
    {
        return [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'matricule' => 'required|string|max:30|unique:personnels',
            'date_embauche' => 'nullable|date',
            'statut' => 'required|string',
            'password' => 'required|string|min:8',
        ];
    }

    public function generatePassword()
    {
        $this->password = Str::random(8);
    }

    public function save()
    {
        $this->validate();

        // Create User
        $user = User::create([
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Assign Role
        $user->assignRole('professeur');

        // Create Personnel profile
        Personnel::create([
            'user_id' => $user->id,
            'matricule' => $this->matricule,
            'fonction' => 'Professeur',
            'type_personnel' => 'Enseignant',
            'date_embauche' => $this->date_embauche ?: null,
            'statut' => $this->statut,
        ]);

        session()->flash('status', 'Professeur créé avec succès.');
        return $this->redirect(route('admin.professeurs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.professeurs.create')->layout('components.layouts.dashboard');
    }
}
