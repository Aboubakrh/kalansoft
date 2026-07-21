<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $guarded = ['id'];

    public function cours(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function eleves(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Core\Eleve::class, 'presence_eleves', 'presence_id', 'eleve_id')
            ->withPivot(['statut', 'observation'])
            ->withTimestamps();
    }
}
