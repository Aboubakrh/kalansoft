<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = ['id'];

    public function eleve(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }
}
