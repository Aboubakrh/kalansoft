<?php

declare(strict_types=1);

namespace App\Livewire\Professeur;

use Livewire\Component;
use App\Models\Pedagogy\Cours;
use App\Models\Pedagogy\Presence as PresenceModel;
use App\Models\Core\Eleve;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

class Presence extends Component
{
    public $cours_id;
    public $date;
    public $attendances = [];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        
        $cours = $this->mesCours;
        if ($cours->isNotEmpty()) {
            $this->cours_id = $cours->first()->id;
            $this->loadEleves();
        }
    }

    public function updatedCoursId()
    {
        $this->loadEleves();
    }

    public function updatedDate()
    {
        $this->loadEleves();
    }

    #[Computed]
    public function mesCours()
    {
        $personnel_id = auth()->user()->personnel->id ?? null;
        if (!$personnel_id) {
            return collect();
        }

        return Cours::with(['classe', 'matiere'])
            ->where('enseignant_id', $personnel_id)
            ->get();
    }

    #[Computed]
    public function eleves()
    {
        if (!$this->cours_id) {
            return collect();
        }

        $cours = Cours::find($this->cours_id);
        if (!$cours) {
            return collect();
        }

        return Eleve::whereHas('inscriptions', function($q) use ($cours) {
            $q->where('classe_id', $cours->classe_id)
              ->whereHas('anneeScolaire', function($q2) {
                  $q2->where('est_active', true);
              });
        })->with('user')->orderBy('matricule')->get();
    }

    public function loadEleves()
    {
        $this->attendances = [];
        if (!$this->cours_id || !$this->date) {
            return;
        }

        $eleves = $this->eleves;
        $existingPresence = PresenceModel::with('eleves')
            ->where('cours_id', $this->cours_id)
            ->where('date', $this->date)
            ->first();

        if ($existingPresence) {
            foreach ($existingPresence->eleves as $eleve) {
                $this->attendances[$eleve->id] = [
                    'statut' => $eleve->pivot->statut,
                    'observation' => $eleve->pivot->observation ?? '',
                ];
            }
        }

        // Fill remaining with default "Présent"
        foreach ($eleves as $eleve) {
            if (!isset($this->attendances[$eleve->id])) {
                $this->attendances[$eleve->id] = [
                    'statut' => 'Présent',
                    'observation' => '',
                ];
            }
        }
    }

    public function save()
    {
        $this->validate([
            'cours_id' => 'required|exists:cours,id',
            'date' => 'required|date',
            'attendances' => 'array',
            'attendances.*.statut' => 'required|in:Présent,Absent,Retard',
            'attendances.*.observation' => 'nullable|string|max:255',
        ]);

        $presence = PresenceModel::firstOrCreate([
            'cours_id' => $this->cours_id,
            'date' => $this->date,
        ]);

        $syncData = [];
        foreach ($this->attendances as $eleveId => $data) {
            $syncData[$eleveId] = [
                'statut' => $data['statut'],
                'observation' => $data['observation'] ?: null,
            ];
        }

        $presence->eleves()->sync($syncData);

        session()->flash('success', 'L\'appel a été enregistré avec succès pour cette date.');
    }

    public function render()
    {
        return view('livewire.professeur.presence')
            ->layout('components.layouts.dashboard', ['title' => __('Faire l\'appel')]);
    }
}
