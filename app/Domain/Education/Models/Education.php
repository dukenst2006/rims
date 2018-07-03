<?php

namespace Rims\Domain\Education\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Users\Models\User;

class Education extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'usable'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The users that have this education level.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
