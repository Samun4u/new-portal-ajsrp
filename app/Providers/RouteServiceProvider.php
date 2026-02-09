<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();
            $this->mapWebRoutes();
            $this->mapUserRoutes();
            $this->mapAdminRoutes();
            $this->mapSAdminRoutes();
            $this->mapSaasRoutes();
        });
    }

    protected function mapWebRoutes()
    {
        Route::middleware(['installed', 'web', 'version.update'])
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }


    protected function mapUserRoutes()
    {
        Route::middleware(['installed', 'web', 'auth', 'user', 'is_email_verify', 'version.update', '2fa_verify', 'common'])
            ->prefix('user')
            ->as('user.')
            ->group(base_path('routes/user.php'));
    }
    protected function mapAdminRoutes()
    {
        Route::middleware(['installed', 'web', 'auth', 'admin', 'is_email_verify', 'version.update', 'addon.update'])
            ->prefix('admin')
            ->as('admin.')
            ->group(base_path('routes/admin.php'));
    }

    protected function mapSAdminRoutes()
    {
        Route::middleware(['installed', 'web', 'auth', 'sadmin', 'is_email_verify', 'version.update', 'addon.update'])
            ->prefix('super-admin')
            ->as('super-admin.')
            ->group(base_path('routes/sadmin.php'));
    }

    protected function mapSaasRoutes()
    {
        if (isAddonInstalled('ENCYSAAS') > 0) {
            Route::middleware(['installed', 'web', 'auth', 'is_email_verify', 'version.update'])
                ->group(base_path('routes/addon/saas.php'));
        }
    }


    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
