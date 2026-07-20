<?php

namespace App\Livewire\Pedagogy\EmploisDuTemps;

use Livewire\Component;
use App\Models\Pedagogy\Classe;
use App\Models\Pedagogy\Cours;
use App\Models\Pedagogy\Salle;
use App\Models\Pedagogy\EmploiDuTemps;
use App\Models\Core\Personnel;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $classe_id;
    public $enseignant_id = '';
    
    // Modal states
    public $showModal = false;
    public $isEditing = false;
    public $emploi_id = null;
    
    // Form fields
    public $form_cours_id;
    public $form_salle_id;
    public $form_jour;
    public $form_heure_debut;
    public $form_heure_fin;

    public function mount()
    {
        $firstClasse = Classe::first();
        if ($firstClasse) {
            $this->classe_id = $firstClasse->id;
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $emploi = EmploiDuTemps::findOrFail($id);
        $this->emploi_id = $emploi->id;
        $this->form_cours_id = $emploi->cours_id;
        $this->form_salle_id = $emploi->salle_id;
        $this->form_jour = $emploi->jour;
        $this->form_heure_debut = substr($emploi->heure_debut, 0, 5); // 08:00
        $this->form_heure_fin = substr($emploi->heure_fin, 0, 5);
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->emploi_id = null;
        $this->form_cours_id = '';
        $this->form_salle_id = '';
        $this->form_jour = 'Lundi';
        $this->form_heure_debut = '08:00';
        $this->form_heure_fin = '10:00';
    }

    public function save()
    {
        $this->validate([
            'form_cours_id' => 'required|exists:cours,id',
            'form_salle_id' => 'required|exists:salles,id',
            'form_jour' => 'required|string',
            'form_heure_debut' => 'required|date_format:H:i',
            'form_heure_fin' => 'required|date_format:H:i|after:form_heure_debut',
        ]);

        // Basic conflict validation
        $conflictQuery = EmploiDuTemps::where('jour', $this->form_jour)
            ->where(function($query) {
                $query->whereBetween('heure_debut', [$this->form_heure_debut, $this->form_heure_fin])
                      ->orWhereBetween('heure_fin', [$this->form_heure_debut, $this->form_heure_fin])
                      ->orWhere(function($q) {
                          $q->where('heure_debut', '<=', $this->form_heure_debut)
                            ->where('heure_fin', '>=', $this->form_heure_fin);
                      });
            })
            ->where(function($query) {
                $query->where('salle_id', $this->form_salle_id)
                      ->orWhere('classe_id', $this->classe_id);
            });

        if ($this->isEditing) {
            $conflictQuery->where('id', '!=', $this->emploi_id);
        }

        if ($conflictQuery->exists()) {
            $this->addError('conflict', 'Conflit détecté : La salle ou la classe est déjà occupée à cette heure.');
            return;
        }

        if ($this->isEditing) {
            $emploi = EmploiDuTemps::find($this->emploi_id);
            $emploi->update([
                'cours_id' => $this->form_cours_id,
                'salle_id' => $this->form_salle_id,
                'jour' => $this->form_jour,
                'heure_debut' => $this->form_heure_debut,
                'heure_fin' => $this->form_heure_fin,
            ]);
        } else {
            EmploiDuTemps::create([
                'classe_id' => $this->classe_id,
                'cours_id' => $this->form_cours_id,
                'salle_id' => $this->form_salle_id,
                'jour' => $this->form_jour,
                'heure_debut' => $this->form_heure_debut,
                'heure_fin' => $this->form_heure_fin,
            ]);
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        EmploiDuTemps::find($id)?->delete();
    }

    public function render()
    {
        $classes = Classe::all();
        $professeurs = Personnel::where('fonction', 'Professeur')->get();
        $cours = Cours::where('classe_id', $this->classe_id)->with('matiere', 'enseignant')->get();
        $salles = Salle::all();

        $query = EmploiDuTemps::with(['cours.matiere', 'cours.enseignant', 'salle'])
            ->where('classe_id', $this->classe_id);

        if (!empty($this->enseignant_id)) {
            $query->whereHas('cours', function($q) {
                $q->where('enseignant_id', $this->enseignant_id);
            });
        }

        $emplois = $query->get();

        return view('livewire.pedagogy.emplois-du-temps.index', [
            'classes' => $classes,
            'professeurs' => $professeurs,
            'coursList' => $cours,
            'salles' => $salles,
            'emplois' => $emplois
        ])->layout('components.layouts.dashboard');
    }
}
