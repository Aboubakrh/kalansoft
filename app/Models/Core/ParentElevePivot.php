<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ParentElevePivot extends Pivot
{
        protected $table = 'parent_eleves';
    protected $guarded = ['id'];
}
