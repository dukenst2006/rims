<?php

namespace Rims\Domain\Users\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Rims\App\Traits\Eloquent\Ordering\OrderableTrait;
use Rims\Domain\Education\Models\Education;

class UserEducation extends Model
{
    protected $table = 'user_education';

    protected $fillable = [
        'name',
        'course',
        'speciality',
        'started_at',
        'ended_at'
    ];

    protected $dates = [
        'started_at',
        'ended_at',
    ];

    public function getStartedAtAttribute($value)
    {
        return $value == null ? $value : Carbon::parse($value)->toDateString();
    }

    public function getEndedAtAttribute($value)
    {
        return $value == null ? $value : Carbon::parse($value)->toDateString();
    }

    /**
     * Get user that belongs to this education achievement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get user's school education level.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function education()
    {
        return $this->belongsTo(Education::class);
    }
}
