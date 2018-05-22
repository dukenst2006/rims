<?php

namespace Rims\Domain\Education\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserEducation extends Pivot
{
    protected $table = 'user_education';

    protected $dates = [
        'started_at',
        'ended_at',
    ];
}
