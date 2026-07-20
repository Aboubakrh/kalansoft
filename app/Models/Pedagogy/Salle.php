<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $guarded = ['id'];

    public function classes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Classe::class);
    }

    public function emploisDuTemps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmploiDuTemps::class);
    }
}
