<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $guarded = ['id'];

    public function serie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function salle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    public function inscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function cours(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cours::class);
    }

    public function fraisScolarite(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Finance\FraisScolarite::class);
    }
}
