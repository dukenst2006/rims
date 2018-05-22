<?php

namespace Rims\App\Providers;

use Illuminate\Support\ServiceProvider;
use Rims\Domain\Areas\Models\Area;
use Rims\Domain\Areas\Observers\AreaObserver;
use Rims\Domain\Categories\Models\Category;
use Rims\Domain\Categories\Observers\CategoryObserver;
use Rims\Domain\Education\Models\Education;
use Rims\Domain\Education\Observers\EducationObserver;
use Rims\Domain\Languages\Models\Language;
use Rims\Domain\Languages\Observers\LanguageObserver;
use Rims\Domain\Levels\Models\Level;
use Rims\Domain\Levels\Observers\LevelObserver;
use Rims\Domain\Skills\Models\Skill;
use Rims\Domain\Skills\Observers\SkillObserver;
use Rims\Domain\Users\Models\Role;
use Rims\Domain\Users\Observers\RoleObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //model observers
        Area::observe(AreaObserver::class);
        Category::observe(CategoryObserver::class);
        Education::observe(EducationObserver::class);
        Level::observe(LevelObserver::class);
        Skill::observe(SkillObserver::class);
        Language::observe(LanguageObserver::class);
        Role::observe(RoleObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
