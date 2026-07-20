<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PresencePersonnelPivot extends Pivot
{
        protected $table = 'presence_personnels';
    protected $guarded = ['id'];
}
