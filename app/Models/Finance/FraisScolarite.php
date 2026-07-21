<?php

declare(strict_types=1);

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class FraisScolarite extends Model
{
        protected $table = 'frais_scolarite';
    protected $guarded = ['id'];

    public function classe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Pedagogy\Classe::class);
    }

    public function anneeScolaire(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\AnneeScolaire::class);
    }
}
