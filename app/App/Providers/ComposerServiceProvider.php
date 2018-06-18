<?php

namespace Rims\App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Rims\App\ViewComposers\AreasComposer;
use Rims\App\ViewComposers\CategoriesComposer;
use Rims\App\ViewComposers\CountriesComposer;
use Rims\App\ViewComposers\EducationComposer;
use Rims\App\ViewComposers\LanguagesComposer;
use Rims\App\ViewComposers\LevelsComposer;
use Rims\App\ViewComposers\PermissionsComposer;
use Rims\App\ViewComposers\PlansComposer;
use Rims\App\ViewComposers\RolesComposer;
use Rims\App\ViewComposers\SkillsComposer;
use Rims\App\ViewComposers\UserCompaniesComposer;
use Rims\App\ViewComposers\UserFiltersComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //plans
        View::composer([
            'subscriptions.index'
        ], PlansComposer::class);

        //countries
        View::composer([
            'account.twofactor.index'
        ], CountriesComposer::class);

//        //categories
//        View::composer([
//            'layouts.partials._navigation',
//            'jobs.*'
//        ], CategoriesComposer::class);
//
//        //education
//        View::composer([
//            'layouts.partials._navigation',
//            'jobs.*'
//        ], EducationComposer::class);
//
//        //levels
//        View::composer([
//            'jobs.*'
//        ], LevelsComposer::class);
//
//        //skills
//        View::composer([
//            'jobs.*'
//        ], SkillsComposer::class);
//
//        //languages
//        View::composer([
//            'jobs.*'
//        ], LanguagesComposer::class);

        //areas
        View::composer('*', AreasComposer::class);

        //user companies
        View::composer('*', UserCompaniesComposer::class);

        //user filters mappings
        View::composer([
            'admin.users.partials._filters'
        ], UserFiltersComposer::class);

        //roles list
        View::composer([
            'admin.users.roles.partials.forms._roles',
            'admin.users.user.roles.index'
        ], RolesComposer::class);

        //permissions list
        View::composer([
            'admin.users.roles.partials.forms._permissions',
        ], PermissionsComposer::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserCompaniesComposer::class);
        $this->app->singleton(AreasComposer::class);
        $this->app->singleton(CategoriesComposer::class);
        $this->app->singleton(EducationComposer::class);
        $this->app->singleton(LevelsComposer::class);
        $this->app->singleton(SkillsComposer::class);
        $this->app->singleton(LanguagesComposer::class);
    }
}
