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
use Rims\Domain\Jobs\Filters\JobFilters;
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
        'cost',
        'saleCost',
        'isOpenForRestore',
        'isPremium',
        'isPastDeadline'
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
        'currency'
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
            'approved' => true,
            'published_at' => !$this->isPublished ? Carbon::now() : $this->published_at
        ]);

        $this->approveSkills();
        $this->approveCategories();
    }

    /**
     * Approve job skills.
     */
    public function approveSkills()
    {
        $this->skills()->unapproved()->update([
            'approved' => true
        ]);
    }

    /**
     * Approve job categories.
     */
    public function approveCategories()
    {
        $this->categories()->unapproved()->update([
            'approved' => true
        ]);
    }

    /**
     * Get payment gateway specific cost.
     *
     * @param string $gateway
     * @return float|int|mixed
     */
    public function gatewayCost($gateway = 'stripe')
    {
        switch ($gateway):
            case 'stripe':
                return $this->cost * 100;
            default:
                return $this->cost;
        endswitch;
    }

    /**
     * Return if job is past deadline.
     *
     * @return bool
     */
    public function getIsPastDeadlineAttribute()
    {
        return optional($this->closed_at)->isPast();
    }

    /**
     * Return if job is premium.
     *
     * @return mixed
     */
    public function getIsPremiumAttribute()
    {
        $categories = $this->categories()->whereHas('category', function (Builder $builder) {
            return $builder->where('needs_auth', true);
        })->count();


        if ($categories == 0) {
            return false;
        }

        return true;
    }

    /**
     * Return job cost.
     *
     * @return mixed
     */
    public function getCostAttribute()
    {

        // get sales cost
        $salesCost = $this->saleCost;

        // get categories count
        $categoriesCount = $this->categories->count();

        // get unapproved skills count
        $unapprovedSkillsCount = $this->skills->where('approved', false)->count();

        // sum cost for unapproved skills
        $unapprovedSkillsCost = $this->skills->where('approved', false)->map(function ($skill, $key) {
            return $skill->skill->price;
        })->sum();

        // get unapproved categories count
        $unapprovedCategories = $this->categories->where('approved', false)->count();

        // get unapproved categories cost
        $unapprovedCategoriesCost = $this->categories->where('approved', false)->map(function ($category, $key) {
            return $category->category->price;
        })->sum();

        // init cost
        $cost = $unapprovedSkillsCost;

        // check if job published
        if ($this->isPublished) {

            // check if job has unapproved categories
            if ($unapprovedCategories > 0) {
                $cost = $unapprovedCategoriesCost;
            }

            // check if job has unapproved skills and with no categories
            if ($unapprovedSkillsCount > 0 && $categoriesCount == 0) {
                $cost = $unapprovedSkillsCost;
            }

            // check if job has any payments
            if ($salesCost > 0) {

                if ($categoriesCount > 0) {

                    // check if job has unapproved categories
                    if ($unapprovedCategories > 0) {
                        $cost = $unapprovedCategoriesCost;
                    } else {
                        $cost = 0;
                    }
                }
            }

        }

        // check if job is not published
        if (!$this->isPublished) {

            // check if job has unapproved categories
            if ($unapprovedCategories > 0) {
                $cost = $unapprovedCategoriesCost;
            }

            // check if job has unapproved skills and with no categories
            if ($unapprovedSkillsCount > 0 && $categoriesCount == 0) {
                $cost = $unapprovedSkillsCost;
            }

        }

        return $cost;
    }

    /**
     * Return job sales cost.
     *
     * @return mixed
     */
    public function getSaleCostAttribute()
    {
        return $this->sales->map(function ($sale, $key) {
            return $sale->sale_price;
        })->sum();
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
     * Return whether job is published.
     *
     * @return bool
     */
    public function getIsOpenForRestoreAttribute()
    {
        if (Carbon::now()->diffInDays($this->closed_at) == 7) {
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

        // get categories count
        $categoriesCount = $this->categories->count();

        if ($this->isPublished) {   // checkout if published

            if ($this->skills->where('approved', false)->count() > 0 && $categoriesCount) { // checkout if skills changed
                return true;
            }

            if ($this->categories->where('approved', false)->count() > 0) { // checkout if categories changed
                return true;
            }
        }


        if (!$this->isPublished) {   // checkout if not published
            if ($this->education->count() == 0) {   // checkout if not published
                return false;
            }

            if ($this->skills->count() == 0) {
                return false;
            }

            if ($this->requirements->count() == 0) {
                return false;
            }

            if ($this->categories->count() == 0) {
                return false;
            }

            // checkout if not published and checkout requirements met
            return true;
        }

        return false;
    }

    /**
     * Check if job has a given skill.
     *
     * @param Skill $skill
     * @return int
     */
    public function hasSkill(Skill $skill)
    {
        return $this->skills->where('skill_id', $skill->id)->count();
    }

    /**
     * Filters the result.
     *
     * @param Builder $builder
     * @param $request
     * @param array $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new JobFilters($request))->add($filters)->filter($builder);
    }

    /**
     * Get query for jobs in given area.
     *
     * @param Area $area
     * @param Builder $builder
     * @return Builder
     */
    public function scopeInArea(Builder $builder, Area $area)
    {
        $areas = $area->descendants->pluck('id')->push($area->id);

        return $builder->whereIn('area_id', $areas);
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
     * Get query for incomplete or unfinished jobs.
     *
     * @param Builder $builder
     * @param int $day
     * @return Builder
     */
    public function scopeIncomplete(Builder $builder, $day = 1)
    {
        return $builder->withTrashed()
            ->where('finished', '=', false)
            ->whereDate('created_at', '<', Carbon::now()->subDays($day));
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
     * Get query for jobs that are not past deadline.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeIsOpen(Builder $builder)
    {
        return $builder->whereNull('closed_at')
            ->orWhereDate('closed_at', '>', Carbon::now()->toDateTimeString());
    }

    /**
     * Get job applications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get job categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(JobCategory::class);
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
     * Get job skills requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skills()
    {
        return $this->hasMany(JobSkill::class);
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
