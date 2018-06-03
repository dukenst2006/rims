<?php

namespace Rims\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Levels\Models\Level;

class JobSkillable extends Model
{
    protected $fillable = [
        'details'
    ];

    /**
     * Get all of the owning skillable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function skillable()
    {
        return $this->morphTo();
    }

    /**
     * Get skill level.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get job that owns skill.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
