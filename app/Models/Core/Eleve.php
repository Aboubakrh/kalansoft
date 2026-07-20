<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Pedagogy\Inscription::class);
    }

    public function parents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ParentEleve::class, 'parent_eleves', 'eleve_id', 'parent_id')->withPivot('lien_parente')->withTimestamps();
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Pedagogy\Note::class);
    }

    public function archives(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Archive::class);
    }
}
