<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    protected $guarded = ['id'];

    public function bulletins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bulletin::class);
    }
}
