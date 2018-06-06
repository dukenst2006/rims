<?php

namespace Rims\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Skills\Models\Skill;

class JobSkill extends Model
{
    protected $fillable = [
        'details'
    ];

    /**
     * Get skill that job belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
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
