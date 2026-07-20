<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class CahierTexte extends Model
{
        protected $table = 'cahiers_texte';
    protected $guarded = ['id'];

    public function cours(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }
}
