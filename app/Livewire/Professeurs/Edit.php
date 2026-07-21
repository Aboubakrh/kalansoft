<?php

namespace App\Livewire\Professeurs;

use Livewire\Component;
use App\Models\Core\User;
use App\Models\Core\Personnel;
use Illuminate\Support\Facades\Hash;

class Edit extends Component
{
    public Personnel $personnel;
    public $nom;
    public $prenom;
    public $email;
    public $matricule;
    public $date_embauche;
    public $statut;
    public $password = '';

    public function mount(Personnel $personnel)
    {
        $this->personnel = $personnel;
        $this->nom = $personnel->user->nom;
        $this->prenom = $personnel->user->prenom;
        $this->email = $personnel->user->email;
        $this->matricule = $personnel->matricule;
        $this->date_embauche = $personnel->date_embauche;
        $this->statut = $personnel->statut;
    }

    public function rules()
    {
        return [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->personnel->user_id,
            'matricule' => 'required|string|max:30|unique:personnels,matricule,' . $this->personnel->id,
            'date_embauche' => 'nullable|date',
            'statut' => 'required|string',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function save()
    {
        $this->validate();

        // Update User
        $userData = [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
        ];
        
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }
        
        $this->personnel->user->update($userData);

        // Update Personnel
        $this->personnel->update([
            'matricule' => $this->matricule,
            'date_embauche' => $this->date_embauche ?: null,
            'statut' => $this->statut,
        ]);

        session()->flash('status', 'Professeur modifié avec succès.');
        return $this->redirect(route('admin.professeurs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.professeurs.edit')->layout('components.layouts.dashboard');
    }
}
