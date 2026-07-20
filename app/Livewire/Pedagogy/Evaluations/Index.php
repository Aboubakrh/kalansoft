<?php

namespace App\Livewire\Pedagogy\Evaluations;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedagogy\Evaluation;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Evaluation::findOrFail($id)->delete();
        session()->flash('status', 'Évaluation supprimée.');
    }

    public function render()
    {
        $query = Evaluation::with(['cours.classe', 'cours.matiere'])
            ->where(function($q) {
                $q->whereHas('cours.classe', function($q2) {
                    $q2->where('nom', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('cours.matiere', function($q2) {
                    $q2->where('nom', 'like', '%' . $this->search . '%');
                })
                ->orWhere('type_evaluation', 'like', '%' . $this->search . '%');
            });

        // Check if user is a teacher and filter accordingly
        if (auth()->user()->hasRole('professeur')) {
            $personnel = \App\Models\Core\Personnel::where('user_id', auth()->id())->first();
            if ($personnel) {
                $query->whereHas('cours', function($q) use ($personnel) {
                    $q->where('enseignant_id', $personnel->id);
                });
            }
        }

        $evaluations = $query->orderBy('date_evaluation', 'desc')->paginate(10);

        return view('livewire.pedagogy.evaluations.index', compact('evaluations'))
            ->layout('components.layouts.dashboard');
    }
}
