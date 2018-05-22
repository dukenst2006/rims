<?php

namespace Rims\Domain\Education\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\App\Traits\Eloquent\Ordering\PivotOrderableTrait;
use Rims\Domain\Users\Models\User;

class Education extends Model
{
    use PivotOrderableTrait;

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
        return $this->belongsToMany(User::class, 'user_education')
            ->using(UserEducation::class)
            ->withTimestamps()
            ->withPivot('name', 'course', 'speciality', 'started_at', 'ended_at');
    }
}
