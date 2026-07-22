<?php

declare(strict_types=1);

namespace App\Livewire\RH;

use App\Models\Core\Personnel;
use App\Models\Finance\Vacation;
use App\Models\Pedagogy\Cours;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Vacations extends Component
{
    public $personnel_id = '';

    public $cours_id = '';

    public $date = '';

    public $nombre_heures = 2.0;

    public $montant = 0;

    public $etat = 'En attente';

    public $filterStatus = '';

    public $filterPersonnel = '';

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
    }

    public function rules()
    {
        return [
            'personnel_id' => 'required|exists:personnels,id',
            'cours_id' => 'required|exists:cours,id',
            'date' => 'required|date',
            'nombre_heures' => 'required|numeric|min:0.5|max:20',
            'montant' => 'required|numeric|min:0',
            'etat' => 'required|in:En attente,Payé,Annulé',
        ];
    }

    #[Computed]
    public function personnels()
    {
        return Personnel::with('user')->get();
    }

    #[Computed]
    public function cours()
    {
        if (! $this->personnel_id) {
            return collect();
        }

        return Cours::with(['classe', 'matiere'])
            ->where('enseignant_id', $this->personnel_id)
            ->get();
    }

    public function updatedPersonnelId()
    {
        $this->cours_id = '';
    }

    public function save()
    {
        $this->validate();

        Vacation::create([
            'personnel_id' => $this->personnel_id,
            'cours_id' => $this->cours_id,
            'date' => $this->date,
            'nombre_heures' => $this->nombre_heures,
            'montant' => $this->montant,
            'etat' => $this->etat,
        ]);

        session()->flash('status', 'Séance de vacation enregistrée avec succès.');
        $this->reset(['personnel_id', 'cours_id', 'montant']);
        $this->nombre_heures = 2.0;
        $this->etat = 'En attente';
        $this->dispatch('close-modal', 'vacation-modal');
    }

    public function markAsPaid($id)
    {
        $vacation = Vacation::findOrFail($id);
        $vacation->update(['etat' => 'Payé']);
        session()->flash('status', 'Vacation marquée comme payée.');
    }

    public function delete($id)
    {
        Vacation::findOrFail($id)->delete();
        session()->flash('status', 'Vacation supprimée.');
    }

    #[Computed]
    public function vacations()
    {
        $query = Vacation::with(['personnel.user', 'cours.matiere', 'cours.classe'])
            ->orderByDesc('date');

        if ($this->filterStatus) {
            $query->where('etat', $this->filterStatus);
        }

        if ($this->filterPersonnel) {
            $query->where('personnel_id', $this->filterPersonnel);
        }

        return $query->get();
    }

    #[Computed]
    public function stats()
    {
        $all = Vacation::all();

        return [
            'total_heures' => $all->sum('nombre_heures'),
            'total_montant' => $all->sum('montant'),
            'montant_paye' => $all->where('etat', 'Payé')->sum('montant'),
            'montant_attente' => $all->where('etat', 'En attente')->sum('montant'),
        ];
    }

    public function render()
    {
        return view('livewire.rh.vacations')
            ->layout('components.layouts.dashboard', ['title' => __('Gestion des Vacations')]);
    }
}
