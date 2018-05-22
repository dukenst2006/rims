<?php

namespace Rims\Domain\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Skills\Models\Skill;

class UserSkill extends Model
{
    protected $fillable = [
        'usable'
    ];

    protected $with = [
        'skill.ancestors',
        'level',
    ];

    /**
     * Get the related skill's level.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the related skill.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * Get the user that owns this skill.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
