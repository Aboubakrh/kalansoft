<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Core\Parametre;

class Index extends Component
{
    // Variables pour l'identité
    public $nom_ecole;
    public $directeur;
    public $telephone;
    public $email;

    public function mount()
    {
        $parametre = Parametre::first();
        if ($parametre) {
            $this->nom_ecole = $parametre->nom_ecole;
            $this->directeur = $parametre->directeur;
            $this->telephone = $parametre->telephone;
            $this->email = $parametre->email;
        }
    }

    public function saveParametres()
    {
        $this->validate([
            'nom_ecole' => 'required|string|max:255',
            'directeur' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
        ]);

        $parametre = Parametre::first() ?? new Parametre();
        $parametre->nom_ecole = $this->nom_ecole;
        $parametre->directeur = $this->directeur;
        $parametre->telephone = $this->telephone;
        $parametre->email = $this->email;
        $parametre->save();

        session()->flash('status', 'Paramètres mis à jour avec succès.');
    }

    public function render()
    {
        return view('livewire.settings.index')->layout('components.layouts.dashboard');
    }
}
