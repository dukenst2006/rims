<?php

namespace Rims\Domain\Portfolios\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Levels\Models\Level;

class PortfolioSkillable extends Model
{
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
     * Get portfolio that owns skill.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
