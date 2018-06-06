<?php

namespace Rims\Domain\Portfolios\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Skills\Models\Skill;

class PortfolioSkill extends Model
{
    /**
     * Get the skill that portfolio belongs to.
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
     * Get portfolio that owns skill.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
