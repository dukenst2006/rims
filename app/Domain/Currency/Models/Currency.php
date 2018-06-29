<?php

namespace Rims\Domain\Currency\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'cc',
        'symbol',
        'name',
        'usable'
    ];
}
