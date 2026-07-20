<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $guarded = ['id'];

    public function eleve(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function anneeScolaire(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function bulletin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Pedagogy\Bulletin::class);
    }
}
