<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    protected $table = 'annees_scolaires';
    protected $guarded = ['id'];

    public function inscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Pedagogy\Inscription::class);
    }

    public function fraisScolarite(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Finance\FraisScolarite::class);
    }

    public function archives(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Archive::class);
    }
}
