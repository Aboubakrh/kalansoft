<?php

declare(strict_types=1);

namespace App\Livewire\Parent;

use Livewire\Component;
use App\Models\Pedagogy\Note;
use App\Models\Pedagogy\Evaluation;
use Livewire\Attributes\Computed;

class Bulletin extends Component
{
    use Traits\HasEleveSelector;

    public function mount()
    {
        $this->mountHasEleveSelector();
    }

    #[Computed]
    public function notes()
    {
        if (!$this->selectedEleveId) {
            return collect();
        }

        return Note::with(['evaluation.cours.matiere'])
            ->where('eleve_id', $this->selectedEleveId)
            ->orderByDesc('created_at')
            ->get();
    }

    #[Computed]
    public function periodes()
    {
        $inscription = $this->currentInscription;
        if (!$inscription || !$inscription->classe_id) {
            return collect();
        }

        return Evaluation::whereHas('cours', function ($q) use ($inscription) {
                $q->where('classe_id', $inscription->classe_id);
            })
            ->select('periode')
            ->distinct()
            ->pluck('periode');
    }

    public function render()
    {
        return view('livewire.parent.bulletin')
            ->layout('components.layouts.dashboard', ['title' => __('Bulletins & Notes')]);
    }
}
