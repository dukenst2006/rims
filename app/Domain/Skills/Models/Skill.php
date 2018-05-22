<?php

namespace Rims\Domain\Skills\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Rims\Domain\Users\Models\User;

class Skill extends Model
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
     * Get users with this skill.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function skilledUsers()
    {
        return $this->morphToMany(User::class, 'skillable', 'user_skills');
    }
}
