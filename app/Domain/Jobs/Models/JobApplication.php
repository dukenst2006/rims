<?php

namespace Rims\Domain\Jobs\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
        'rejected_at',
        'cancelled_at',
        'declined_at'
    ];

    protected $dates = [
        'submitted_at',
        'accepted_at',
        'rejected_at',
        'cancelled_at',
        'declined_at'
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
     * Scope query for finished job applications.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeFinished(Builder $builder)
    {
        return $builder->where("{$this->getTable()}.finished", '=', true);
    }

    /**
     * Scope query for accepted job applications.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeAccepted(Builder $builder)
    {
        return $builder->finished()
            ->whereNotNull('submitted_at')
            ->whereNotNull('accepted_at')
            ->whereNull('rejected_at');
    }

    /**
     * Scope query for rejected job applications.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeRejected(Builder $builder)
    {
        return $builder->finished()
            ->whereNotNull('submitted_at')
            ->whereNotNull('rejected_at')
            ->whereNull('accepted_at');
    }

    /**
     * Scope query for submitted job applications.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeSubmitted(Builder $builder)
    {
        return $builder->finished()->whereNotNull('submitted_at');
    }

    /**
     * Scope query for not cancelled job applications.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeIsNotCancelled(Builder $builder)
    {
        return $builder->finished()->whereNull('cancelled_at');
    }

    /**
     * Scope query for pending (awaiting reply) job applications.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopePending(Builder $builder)
    {
        return $builder->finished()
            ->whereNotNull('submitted_at')
            ->whereNull('accepted_at')
            ->whereNull('rejected_at');
    }

    /**
     * Scope query for drafted (awaiting submission) job applications.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeDrafted(Builder $builder)
    {
        return $builder->finished()
            ->whereNull('submitted_at');
    }

    /**
     * Scope query for incomplete or unfinished job applications.
     *
     * @param Builder $builder
     * @param int $day
     * @return Builder
     */
    public function scopeIncomplete(Builder $builder, $day = 14)
    {
        return $builder->where('finished', '=', false)
            ->whereDate('created_at', '>=', Carbon::now()->subDays($day));
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
