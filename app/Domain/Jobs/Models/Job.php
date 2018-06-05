<?php

namespace Rims\Domain\Jobs\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rims\App\Tenant\Manager;
use Rims\App\Tenant\Scopes\TenantScope;
use Rims\App\Tenant\Traits\ForTenants;
use Rims\App\Traits\Eloquent\Ordering\OrderableTrait;
use Rims\Domain\Areas\Models\Area;
use Rims\Domain\Company\Models\Company;
use Rims\Domain\Jobs\Observers\JobObserver;
use Rims\Domain\Languages\Models\Language;
use Rims\Domain\Skills\Models\Skill;

class Job extends Model
{
    use Sluggable,
        ForTenants,
        OrderableTrait,
        SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job';

    /**
     * The attributes that should be appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'isPublished',
        'isReadyForCheckout',
        'cost'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier',
        'title',
        'slug',
        'overview_short',
        'overview',
        'applicants',
        'type',
        'on_location',
        'salary_min',
        'salary_max',
        'live',
        'approved',
        'finished',
        'published_at',
        'closed_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
        'closed_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        $manager = app(Manager::class);

        if (null !== ($manager->getTenant())) {
            static::addGlobalScope(
                new TenantScope($manager->getTenant())
            );

            static::observe(
                app(JobObserver::class)
            );
        }
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
                'includeTrashed' => true,
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Publish job.
     */
    public function publish()
    {
        $this->update([
            'live' => true,
            'published_at' => Carbon::now()
        ]);
    }

    /**
     * Return whether job is published.
     *
     * @return mixed
     */
    public function getCostAttribute()
    {
        $cost = $this->skills->map(function ($skill, $key) {
            return $skill->skillable->price;
        })->sum();


        return $cost;
    }

    /**
     * Return whether job is published.
     *
     * @return bool
     */
    public function getIsPublishedAttribute()
    {
        if ($this->published_at == null) {
            return false;
        }

        return true;
    }

    /**
     * Return whether job is ready for checkout as a property.
     *
     * @return bool
     */
    public function getIsReadyForCheckoutAttribute()
    {

        if ($this->education->count() == 0) {
            return false;
        }

        if ($this->skills->count() == 0) {
            return false;
        }

        if ($this->languages->count() == 0) {
            return false;
        }

        if ($this->requirements->count() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if job has a given skill.
     *
     * @param Language $language
     * @return int
     */
    public function hasLanguage(Language $language)
    {
        return $this->languages->where('skillable_id', $language->id)->count();
    }

    /**
     * Check if job has a given skill.
     *
     * @param Skill $skill
     * @return int
     */
    public function hasSkill(Skill $skill)
    {
        return $this->skills->where('skillable_id', $skill->id)->count();
    }

    /**
     * Get query for finished jobs.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeFinished(Builder $builder)
    {
        return $builder->where('finished', '=', true);
    }

    /**
     * Get query for live jobs.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeLive(Builder $builder)
    {
        return $builder->where('live', '=', true);
    }

    /**
     * Get query for published jobs.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopePublished(Builder $builder)
    {
        return $builder->whereDate('published_at', '<=', Carbon::now()->toDateTimeString());
    }

    /**
     * Get job sales.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales()
    {
        return $this->hasMany(JobSale::class);
    }

    /**
     * Get job extra requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requirements()
    {
        return $this->hasMany(JobRequirement::class);
    }

    /**
     * Get job language requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages()
    {
        return $this->hasMany(JobSkillable::class)
            ->where('skillable_type', Skill::getActualClassNameForMorph(Language::class));
    }

    /**
     * Get job skills requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skills()
    {
        return $this->hasMany(JobSkillable::class)
            ->where('skillable_type', Skill::getActualClassNameForMorph(Skill::class));
    }

    /**
     * Get job education requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function education()
    {
        return $this->hasMany(JobEducation::class);
    }

    /**
     * Get area that job belongs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get company that owns job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
