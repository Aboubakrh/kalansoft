<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
        protected $table = 'cours';
    protected $guarded = ['id'];

    public function classe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function matiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function enseignant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\Personnel::class, 'enseignant_id');
    }

    public function emploisDuTemps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    public function presences(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function cahiersTexte(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CahierTexte::class);
    }

    public function evaluations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
