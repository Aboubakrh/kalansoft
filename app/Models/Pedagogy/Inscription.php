<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $guarded = ['id'];

    public function eleve(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\Eleve::class);
    }

    public function classe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function anneeScolaire(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\AnneeScolaire::class);
    }

    public function bulletins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bulletin::class);
    }

    public function paiements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Finance\Paiement::class);
    }

    public function totalPaye(): float
    {
        return (float) $this->paiements()->sum('montant');
    }

    public function soldeRestant(): float
    {
        return (float) ($this->frais_scolarite - $this->totalPaye());
    }
}
