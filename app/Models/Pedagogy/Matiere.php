<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $guarded = ['id'];

    public function series(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Serie::class, 'coefficients')->withPivot('valeur');
    }

    public function cours(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cours::class);
    }
}
