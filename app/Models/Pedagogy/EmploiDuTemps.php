<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
        protected $table = 'emplois_du_temps';
    protected $guarded = ['id'];

    public function classe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function cours(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function salle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }
}
