<?php

namespace App\Livewire\Pedagogy\Evaluations;

use Livewire\Component;
use App\Models\Pedagogy\Evaluation;
use App\Models\Pedagogy\Cours;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $cours_id = '';
    public $type_evaluation = 'Devoir';
    public $periode = 'Trimestre 1';
    public $date_evaluation = '';
    public $bareme = 20;
    public $coefficient = 1;

    public function mount()
    {
        $this->date_evaluation = date('Y-m-d');
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

        $eval = Evaluation::create([
            'cours_id' => $this->cours_id,
            'type_evaluation' => $this->type_evaluation,
            'periode' => $this->periode,
            'date_evaluation' => $this->date_evaluation,
            'bareme' => $this->bareme,
            'coefficient' => $this->coefficient,
        ]);

        session()->flash('status', 'Évaluation créée avec succès. Vous pouvez maintenant saisir les notes.');
        return $this->redirect(route('admin.notes.saisie', $eval->id), navigate: true);
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

        return view('livewire.pedagogy.evaluations.create', compact('cours'))
            ->layout('components.layouts.dashboard');
    }
}
