<?php

declare(strict_types=1);

namespace App\Livewire\RH;

use App\Models\Core\Personnel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Pointage extends Component
{
    public $date;

    public $pointages = [];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadPointages();
    }

    public function updatedDate()
    {
        $this->loadPointages();
    }

    public function loadPointages()
    {
        $personnels = $this->personnels;
        $existing = DB::table('presence_personnels')
            ->where('date', $this->date)
            ->get()
            ->keyBy('personnel_id');

        $this->pointages = [];
        foreach ($personnels as $p) {
            $record = $existing->get($p->id);
            $this->pointages[$p->id] = [
                'heure_arrivee' => $record ? ($record->heure_arrivee ? substr((string) $record->heure_arrivee, 0, 5) : '') : '',
                'heure_depart' => $record ? ($record->heure_depart ? substr((string) $record->heure_depart, 0, 5) : '') : '',
            ];
        }
    }

    #[Computed]
    public function personnels()
    {
        return Personnel::with('user')->orderBy('matricule')->get();
    }

    public function setNowArrivee($personnelId)
    {
        $now = Carbon::now()->format('H:i');
        $this->pointages[$personnelId]['heure_arrivee'] = $now;
        $this->saveRow($personnelId);
    }

    public function setNowDepart($personnelId)
    {
        $now = Carbon::now()->format('H:i');
        $this->pointages[$personnelId]['heure_depart'] = $now;
        $this->saveRow($personnelId);
    }

    public function clearRow($personnelId)
    {
        $this->pointages[$personnelId]['heure_arrivee'] = '';
        $this->pointages[$personnelId]['heure_depart'] = '';

        DB::table('presence_personnels')
            ->where('personnel_id', $personnelId)
            ->where('date', $this->date)
            ->delete();

        session()->flash('status', 'Pointage réinitialisé pour la ligne sélectionnée.');
    }

    public function saveRow($personnelId)
    {
        $data = $this->pointages[$personnelId] ?? [];
        $arrivee = ! empty($data['heure_arrivee']) ? $data['heure_arrivee'] : null;
        $depart = ! empty($data['heure_depart']) ? $data['heure_depart'] : null;

        if (! $arrivee && ! $depart) {
            DB::table('presence_personnels')
                ->where('personnel_id', $personnelId)
                ->where('date', $this->date)
                ->delete();
        } else {
            DB::table('presence_personnels')->updateOrInsert(
                ['personnel_id' => $personnelId, 'date' => $this->date],
                [
                    'heure_arrivee' => $arrivee,
                    'heure_depart' => $depart,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        session()->flash('status', 'Pointage mis à jour.');
    }

    public function render()
    {
        return view('livewire.rh.pointage')
            ->layout('components.layouts.dashboard', ['title' => __('Pointage du Personnel')]);
    }
}
