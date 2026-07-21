<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PresenceElevePivot extends Pivot
{
        protected $table = 'presence_eleves';
    protected $guarded = ['id'];
}
