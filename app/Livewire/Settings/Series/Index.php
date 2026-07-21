<?php

namespace App\Livewire\Settings\Series;

use App\Models\Pedagogy\Serie;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $serieId;
    public $nom;
    public $code;
    public $cycle;
    public $ordre;

    protected $rules = [
        'nom' => 'required|string|max:50',
        'code' => 'required|string|max:10|unique:series,code',
        'cycle' => 'nullable|string|max:50',
        'ordre' => 'nullable|integer',
    ];

    public function mount()
    {
        // Init
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['serieId', 'nom', 'code', 'cycle', 'ordre']);
    }

    public function edit($id)
    {
        $this->resetValidation();
        $serie = Serie::findOrFail($id);
        $this->serieId = $serie->id;
        $this->nom = $serie->nom;
        $this->code = $serie->code;
        $this->cycle = $serie->cycle;
        $this->ordre = $serie->ordre;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->serieId) {
            $rules['code'] = 'required|string|max:10|unique:series,code,' . $this->serieId;
        }

        $this->validate($rules);

        Serie::updateOrCreate(
            ['id' => $this->serieId],
            [
                'nom' => $this->nom,
                'code' => $this->code,
                'cycle' => $this->cycle,
                'ordre' => $this->ordre,
            ]
        );

        session()->flash('success', 'Série enregistrée avec succès.');
        $this->dispatch('close-modal', 'serie-modal');
    }

    public function delete($id)
    {
        Serie::findOrFail($id)->delete();
        session()->flash('success', 'Série supprimée avec succès.');
    }

    public function render()
    {
        $series = Serie::where('nom', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy('ordre', 'asc')
            ->paginate(10);

        return view('livewire.settings.series.index', compact('series'))->layout('components.layouts.dashboard');
    }
}
