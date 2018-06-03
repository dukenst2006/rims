<?php

namespace Rims\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;

class JobRequirement extends Model
{
    protected $fillable = [
        'title',
        'details'
    ];

    /**
     * Get all of the owning requireable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function requireable()
    {
        return $this->morphTo();
    }

    /**
     * Get job that owns requirement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
