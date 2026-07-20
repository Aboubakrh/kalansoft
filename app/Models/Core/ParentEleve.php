<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ParentEleve extends Model
{
    protected $table = 'parents';
    protected $guarded = ['id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function eleves(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Eleve::class, 'parent_eleves', 'parent_id', 'eleve_id')->withPivot('lien_parente')->withTimestamps();
    }
}
