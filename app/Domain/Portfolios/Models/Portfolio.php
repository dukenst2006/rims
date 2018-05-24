<?php

namespace Rims\Domain\Portfolios\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Rims\App\Traits\HasApprovals;
use Rims\Domain\Skills\Models\Skill;

class Portfolio extends Model
{
    use SoftDeletes,
        HasApprovals;

    const APPROVAL_PROPERTIES = [
        'title',
        'overview_short',
        'overview',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'free'
    ];

    protected $casts = [
        'free' => 'boolean'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'overview_short',
        'overview',
        'image',
        'price',
        'live',
        'approved',
        'finished',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->identifier = uniqid(true);
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
     * Append file's price if is "free" as attribute.
     *
     * @return bool
     */
    public function getFreeAttribute()
    {
        $free = ($this->price == 0);

        return $free;
    }

    /**
     * Get the portfolio's image.
     *
     * @return string
     */
    public function getImageAttribute($value)
    {
        if ($this->fileable()->getModel()->getTable() === 'users') {
            return Storage::url("users/{$this->fileable->id}/portfolios/{$this->identifier}/image/{$value}");
        }

        if ($this->fileable()->getModel()->getTable() === 'companies') {
            return Storage::url("companies/{$this->fileable->id}/portfolios/{$this->identifier}/image/{$value}");
        }

        return $value;  // return default image
    }

    /**
     * Filter finished files.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeFinished(Builder $builder)
    {
        return $builder->where('finished', true);
    }

    /**
     * Filter live files.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeIsLive(Builder $builder)
    {
        return $builder->where('live', true);
    }

    /**
     * Approve and make file visible(live).
     */
    public function updateToBeVisible()
    {
        $this->update([
            'live' => true,
            'approved' => true,
        ]);
    }

    /**
     * Get uploads owned by portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skills()
    {
        return $this->hasMany(PortfolioSkillable::class)
            ->where('skillable_type', Skill::getActualClassNameForMorph(Skill::class));
    }

    /**
     * Get uploads owned by portfolio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function uploads()
    {
        return $this->hasMany(PortfolioUpload::class);
    }

    /**
     * Get portfolio owning model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fileable()
    {
        return $this->morphTo();
    }
}
