<?php

namespace App\Livewire\Professeur;

use Livewire\Component;
use App\Models\Pedagogy\CahierTexte as CahierTexteModel;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Cours;

class CahierTexte extends Component
{
    public $classe_id = '';
    public $cours_id = '';

    // Modal forms
    public $showModal = false;
    public $form_cours_id = '';
    public $form_date = '';
    public $form_chapitre = '';
    public $form_contenu = '';
    public $form_travail_maison = '';

    public function mount()
    {
        $this->form_date = date('Y-m-d');
    }

    public function rules()
    {
        return [
            'form_cours_id' => 'required|exists:cours,id',
            'form_date' => 'required|date',
            'form_chapitre' => 'required|string|max:255',
            'form_contenu' => 'nullable|string',
            'form_travail_maison' => 'nullable|string',
        ];
    }

    public function create()
    {
        $this->reset(['form_cours_id', 'form_chapitre', 'form_contenu', 'form_travail_maison']);
        $this->form_date = date('Y-m-d');
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Verify that the professor actually teaches this course
        $personnel_id = auth()->user()->personnel->id ?? null;
        $cours = Cours::where('id', $this->form_cours_id)
            ->where('enseignant_id', $personnel_id)
            ->first();

        if (!$cours) {
            $this->addError('form_cours_id', 'Action non autorisée.');
            return;
        }

        CahierTexteModel::create([
            'cours_id' => $this->form_cours_id,
            'date' => $this->form_date,
            'chapitre' => $this->form_chapitre,
            'contenu' => $this->form_contenu,
            'travail_maison' => $this->form_travail_maison,
        ]);

        $this->showModal = false;
        session()->flash('status', 'Séance enregistrée dans le cahier de texte.');
    }

    public function render()
    {
        $personnel_id = auth()->user()->personnel->id ?? null;
        
        // Liste des cours du prof pour le filtre et le form
        $mesCours = Cours::with(['matiere', 'classe'])
            ->where('enseignant_id', $personnel_id)
            ->get();

        $classesIds = $mesCours->pluck('classe_id')->unique();
        $classes = Classe::whereIn('id', $classesIds)->get();

        $query = CahierTexteModel::with(['cours.matiere', 'cours.classe', 'cours.enseignant.user'])
            ->whereHas('cours', function($q) use ($personnel_id) {
                $q->where('enseignant_id', $personnel_id);
            });

        if (!empty($this->classe_id)) {
            $query->whereHas('cours', function($q) {
                $q->where('classe_id', $this->classe_id);
            });
        }
        
        if (!empty($this->cours_id)) {
            $query->where('cours_id', $this->cours_id);
        }

        $cahiers = $query->orderByDesc('date')->get();

        return view('livewire.professeur.cahier-texte', [
            'classes' => $classes,
            'mesCours' => $mesCours,
            'cahiers' => $cahiers,
        ])->layout('components.layouts.dashboard');
    }
}
