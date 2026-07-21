<?php

namespace App\Livewire\Pedagogy\Evaluations;

use Livewire\Component;
use App\Models\Pedagogy\Evaluation;
use App\Models\Pedagogy\Cours;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public Evaluation $evaluation;
    public $cours_id;
    public $type_evaluation;
    public $periode;
    public $date_evaluation;
    public $bareme;
    public $coefficient;

    public function mount(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
        $this->cours_id = $evaluation->cours_id;
        $this->type_evaluation = $evaluation->type_evaluation;
        $this->periode = $evaluation->periode;
        $this->date_evaluation = $evaluation->date_evaluation;
        $this->bareme = $evaluation->bareme;
        $this->coefficient = $evaluation->coefficient;
    }

    public function rules()
    {
        return [
            'cours_id' => 'required|exists:cours,id',
            'type_evaluation' => 'required|string',
            'periode' => 'required|string',
            'date_evaluation' => 'required|date',
            'bareme' => 'required|numeric|min:1',
            'coefficient' => 'required|numeric|min:0.5',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->evaluation->update([
            'cours_id' => $this->cours_id,
            'type_evaluation' => $this->type_evaluation,
            'periode' => $this->periode,
            'date_evaluation' => $this->date_evaluation,
            'bareme' => $this->bareme,
            'coefficient' => $this->coefficient,
        ]);

        session()->flash('status', 'Évaluation modifiée avec succès.');
        return $this->redirect(route('admin.evaluations.index'), navigate: true);
    }

    public function render()
    {
        $query = Cours::with(['classe', 'matiere']);
            
        if (Auth::user()->hasRole('professeur')) {
            $query->whereHas('enseignant', function($q) {
                $q->where('user_id', Auth::id());
            });
        }
        
        $cours = $query->get();

        return view('livewire.pedagogy.evaluations.edit', compact('cours'))
            ->layout('components.layouts.dashboard');
    }
}
