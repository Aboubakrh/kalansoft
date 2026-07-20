<?php

namespace App\Livewire\RH;

use Livewire\Component;
use App\Models\Core\Personnel;
use App\Models\Core\User;

class Index extends Component
{
    public $search = '';

    // Form fields
    public $personnelId;
    public $nom = '';
    public $email = '';
    public $matricule = '';
    public $fonction = '';
    public $type_personnel = 'Titulaire';
    public $role = 'professeur';

    public $isEditing = false;

    public function rules()
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'matricule' => 'required|string|max:30|unique:personnels,matricule,' . $this->personnelId,
            'fonction' => 'required|string|max:100',
            'type_personnel' => 'required|string|max:50',
            'role' => 'required|string|in:admin,professeur,comptable,surveillant',
        ];

        if (!$this->isEditing) {
            $rules['email'] = 'required|email|unique:users,email';
        } else {
            // Find the user id linked to this personnel
            $personnel = Personnel::find($this->personnelId);
            $userId = $personnel ? $personnel->user_id : null;
            $rules['email'] = 'required|email|unique:users,email,' . $userId;
        }

        return $rules;
    }

    public function create()
    {
        $this->reset(['personnelId', 'nom', 'email', 'matricule', 'fonction', 'type_personnel', 'role']);
        $this->type_personnel = 'Titulaire';
        $this->role = 'professeur';
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $personnel = Personnel::with('user')->findOrFail($id);
        $this->personnelId = $personnel->id;
        $this->nom = $personnel->user->name;
        $this->email = $personnel->user->email;
        $this->matricule = $personnel->matricule;
        $this->fonction = $personnel->fonction;
        $this->type_personnel = $personnel->type_personnel;
        $this->role = $personnel->user->roles->first()->name ?? 'professeur';
        
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        \DB::transaction(function () {
            if ($this->isEditing) {
                $personnel = Personnel::with('user')->findOrFail($this->personnelId);
                $user = $personnel->user;
                $user->update([
                    'name' => $this->nom,
                    'email' => $this->email,
                ]);
                $user->syncRoles([$this->role]);

                $personnel->update([
                    'matricule' => $this->matricule,
                    'fonction' => $this->fonction,
                    'type_personnel' => $this->type_personnel,
                ]);
            } else {
                // Generate a default password (e.g. matricule)
                $user = User::create([
                    'name' => $this->nom,
                    'email' => $this->email,
                    'password' => \Hash::make($this->matricule),
                ]);
                $user->assignRole($this->role);

                Personnel::create([
                    'user_id' => $user->id,
                    'matricule' => $this->matricule,
                    'fonction' => $this->fonction,
                    'type_personnel' => $this->type_personnel,
                    'date_embauche' => now(),
                    'statut' => 'Actif',
                ]);
            }
        });

        session()->flash('status', $this->isEditing ? 'Membre du personnel modifié.' : 'Membre du personnel ajouté. Mot de passe par défaut : ' . $this->matricule);
        $this->dispatch('close-modal', 'personnel-modal');
    }

    public function delete($id)
    {
        $personnel = Personnel::findOrFail($id);
        // Deleting the user will cascade delete the personnel if configured, or we delete both
        $user = $personnel->user;
        $personnel->delete();
        if ($user) {
            $user->delete();
        }
        
        session()->flash('status', 'Membre du personnel supprimé.');
    }

    public function render()
    {
        $personnels = Personnel::with('user')
            ->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orWhere('matricule', 'like', '%' . $this->search . '%')
            ->orWhere('fonction', 'like', '%' . $this->search . '%')
            ->get();
        
        $totalPersonnel = $personnels->count();
        $titulaires = $personnels->where('type_personnel', 'Titulaire')->count();
        $vacataires = $personnels->where('type_personnel', 'Vacataire')->count();

        return view('livewire.rh.index', [
            'personnels' => $personnels,
            'totalPersonnel' => $totalPersonnel,
            'titulaires' => $titulaires,
            'vacataires' => $vacataires,
        ])->layout('components.layouts.dashboard');
    }
}
