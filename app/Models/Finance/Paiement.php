<?php

declare(strict_types=1);

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $guarded = ['id'];

    public function inscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Pedagogy\Inscription::class);
    }

    public function caissier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'caissier_id');
    }
}
