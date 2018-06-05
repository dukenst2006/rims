<?php

namespace Rims\App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Rims\Domain\Auth\Events\UserRequestedActivationEmail;
use Rims\Domain\Auth\Events\UserSignedUp;
use Rims\Domain\Auth\Listeners\CreateDefaultTeam;
use Rims\Domain\Auth\Listeners\SendActivationEmail;
use Rims\Domain\Company\Listeners\CompanyUserEventSubscriber;
use Rims\Domain\Jobs\Events\JobSaleCreated;
use Rims\Domain\Jobs\Listeners\SendEmailToUser;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserSignedUp::class => [
            CreateDefaultTeam::class,
            SendActivationEmail::class,
        ],
        UserRequestedActivationEmail::class => [
            SendActivationEmail::class,
        ],
        JobSaleCreated::class => [
            SendEmailToUser::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        CompanyUserEventSubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
