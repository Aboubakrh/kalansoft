<?php

declare(strict_types=1);

namespace App\Models\Pedagogy;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Coefficient extends Pivot
{
    protected $table = 'coefficients';
    protected $guarded = ['id'];
}
