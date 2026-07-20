<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cours(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Pedagogy\Cours::class, 'enseignant_id');
    }

    public function vacations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Finance\Vacation::class);
    }

    public function presences(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PresencePersonnelPivot::class);
    }
}
