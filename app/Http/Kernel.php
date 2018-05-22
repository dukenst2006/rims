<?php

namespace Rims\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Rims\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Rims\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Rims\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Rims\Http\Middleware\VerifyCsrfToken::class,
            \Rims\Http\Middleware\Admin\Impersonate::class,
        ],

        'tenant' => [
            \Rims\Http\Middleware\Tenant\TenantMiddleware::class,
            \Rims\Http\Middleware\Tenant\TenantConfigMiddleware::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Rims\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'confirmation_token.expired' => \Rims\Http\Middleware\ChecksExpiredConfirmationTokens::class,
        'role' => \Rims\Http\Middleware\AbortIfHasNoRole::class,
        'permission' => \Rims\Http\Middleware\AbortIfHasNoPermission::class,
        'auth.register' => \Rims\Http\Middleware\AuthenticateRegister::class,
        'subscription.active' => \Rims\Http\Middleware\Subscription\RedirectIfNotActive::class,
        'subscription.notcancelled' => \Rims\Http\Middleware\Subscription\RedirectIfCancelled::class,
        'subscription.cancelled' => \Rims\Http\Middleware\Subscription\RedirectIfNotCancelled::class,
        'subscription.customer' => \Rims\Http\Middleware\Subscription\RedirectIfNotCustomer::class,
        'subscription.inactive' => \Rims\Http\Middleware\Subscription\RedirectIfNotInactive::class,
        'subscription.team' => \Rims\Http\Middleware\Subscription\RedirectIfNoTeamPlan::class,
        'subscription.owner' => \Rims\Http\Middleware\Subscription\RedirectIfNotSubscriptionOwner::class,
    ];
}
