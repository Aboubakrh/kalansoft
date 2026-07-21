<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    protected $guarded = ['id'];

    public function inscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function decision(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Decision::class);
    }

    public function appreciation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appreciation::class);
    }
}
