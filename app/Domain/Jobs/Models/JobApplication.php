<?php

namespace Rims\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Rims\Domain\Users\Models\User;

class JobApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'identifier',
        'cv',
        'details',
        'finished',
        'submitted_at',
        'accepted_at',
        'rejected_at'
    ];

    protected $dates = [
        'submitted_at',
        'accepted_at',
        'rejected_at'
    ];

    protected $appends = [
        'cvPath',
        'submitted'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            $application->identifier = uniqid(true);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'identifier';
    }

    /**
     * Get CV's upload path.
     *
     * @return string
     */
    public function getCVFullPath()
    {
        $job = $this->job;

        return "jobs/{$job->identifier}/applications/{$this->identifier}/cv/{$this->cv}";
    }

    /**
     * Get formatted CV name.
     *
     * @param  string $value
     * @return string
     */
    public function getCvAttribute($value)
    {
        if ($value === 'Undefined' || $value == null) {
            return null;
        }

        return $value;
    }

    /**
     * Get the user's CV upload url.
     *
     * @return string
     */
    public function getCvPathAttribute()
    {
        if ($this->cv === 'Undefined' || $this->cv == null) {
            return null;
        }

        $path = $this->getCVFullPath();

        return Storage::disk('local')->url($path);
    }

    /**
     * Return if application submitted.
     *
     * @return string
     */
    public function getSubmittedAttribute()
    {
        if ($this->submitted_at == null) {
            return false;
        }

        return true;
    }

    /**
     * Get job that owns application.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get user that owns application.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
