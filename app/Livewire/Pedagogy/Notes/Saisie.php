<?php

namespace App\Livewire\Pedagogy\Notes;

use Livewire\Component;
use App\Models\Pedagogy\Evaluation;
use App\Models\Pedagogy\Note;
use App\Models\Pedagogy\Inscription;

class Saisie extends Component
{
    public $evaluation;
    
    // Associative array: eleve_id => ['valeur' => '', 'observation' => '']
    public $notes = [];

    public function mount($evaluation_id)
    {
        $this->evaluation = Evaluation::with(['cours.classe', 'cours.matiere'])->findOrFail($evaluation_id);
        
        // Authorization check
        if (auth()->user()->hasRole('professeur')) {
            $personnel = \App\Models\Core\Personnel::where('user_id', auth()->id())->first();
            if (!$personnel || $this->evaluation->cours->enseignant_id !== $personnel->id) {
                abort(403, "Vous n'êtes pas autorisé à modifier les notes de cette évaluation.");
            }
        }

        // Fetch students in this class
        $inscriptions = Inscription::with('eleve.user')
            ->where('classe_id', $this->evaluation->cours->classe_id)
            ->where('statut', 'Inscrit')
            ->get();

        // Fetch existing notes for this evaluation
        $existingNotes = Note::where('evaluation_id', $this->evaluation->id)
            ->get()
            ->keyBy('eleve_id');

        foreach ($inscriptions as $insc) {
            $eleveId = $insc->eleve_id;
            if (isset($existingNotes[$eleveId])) {
                $this->notes[$eleveId] = [
                    'valeur' => $existingNotes[$eleveId]->valeur,
                    'observation' => $existingNotes[$eleveId]->observation
                ];
            } else {
                $this->notes[$eleveId] = [
                    'valeur' => '',
                    'observation' => ''
                ];
            }
        }
    }

    public function rules()
    {
        return [
            'notes.*.valeur' => 'nullable|numeric|min:0|max:' . $this->evaluation->bareme,
            'notes.*.observation' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'notes.*.valeur.max' => 'La note ne peut pas dépasser le barème de ' . (int)$this->evaluation->bareme,
            'notes.*.valeur.numeric' => 'Doit être un nombre.',
        ];
    }

    public function save()
    {
        $this->validate();

        $count = 0;
        foreach ($this->notes as $eleve_id => $data) {
            if ($data['valeur'] !== '' && $data['valeur'] !== null) {
                Note::updateOrCreate(
                    [
                        'evaluation_id' => $this->evaluation->id,
                        'eleve_id' => $eleve_id,
                    ],
                    [
                        'valeur' => $data['valeur'],
                        'observation' => $data['observation'] ?? null,
                    ]
                );
                $count++;
            } else {
                // If the user erased the note, we might want to delete it or just skip. 
                // Let's delete it if it exists.
                Note::where('evaluation_id', $this->evaluation->id)
                    ->where('eleve_id', $eleve_id)
                    ->delete();
            }
        }

        session()->flash('status', $count . ' notes enregistrées avec succès.');
    }

    public function render()
    {
        // We re-fetch students to order them alphabetically
        $inscriptions = Inscription::with('eleve.user')
            ->where('classe_id', $this->evaluation->cours->classe_id)
            ->where('statut', 'Inscrit')
            ->get()
            ->sortBy('eleve.user.nom');

        return view('livewire.pedagogy.notes.saisie', compact('inscriptions'))
            ->layout('components.layouts.dashboard');
    }
}
