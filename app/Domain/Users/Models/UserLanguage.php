<?php

namespace Rims\Domain\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Rims\Domain\Languages\Models\Language;
use Rims\Domain\Levels\Models\Level;

class UserLanguage extends Model
{
    protected $fillable = [
        'usable'
    ];

    protected $with = [
        'language.ancestors',
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
     * Get the related language.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
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
