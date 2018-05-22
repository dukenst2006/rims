<?php

namespace Rims\Domain\Languages\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Rims\Domain\Users\Models\User;

class Language extends Model
{
    use NodeTrait;

    protected $fillable = [
        'name',
        'slug',
        'usable',
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
     * Get users skilled with this language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function skilledUsers()
    {
        return $this->morphToMany(User::class, 'skillable', 'user_skills');
    }
}
