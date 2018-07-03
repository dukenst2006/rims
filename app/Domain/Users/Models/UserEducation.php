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

    protected $appends = [
        'enrollmentYear',
        'graduationYear'
    ];

    /**
     * Get enrollment year from start date.
     *
     * @return int|null
     */
    public function getEnrollmentYearAttribute()
    {
        return $this->started_at == null ? null : Carbon::parse($this->started_at)->year;
    }

    /**
     * Get graduation year from end date.
     *
     * @return int|null
     */
    public function getGraduationYearAttribute()
    {
        if ($this->ended_at == null) {
            return $this->started_at == null ? null : 'Ongoing';
        } else {
            return Carbon::parse($this->ended_at)->year;
        }
    }

    /**
     * Get formatted start date.
     *
     * @param $value
     * @return string
     */
    public function getStartedAtAttribute($value)
    {
        return $value == null ? $value : Carbon::parse($value)->toDateString();
    }

    /**
     * Get formatted end date.
     *
     * @param $value
     * @return string
     */
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
