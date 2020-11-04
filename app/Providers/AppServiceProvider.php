<?php
/*
|--------------------------------------------------------------------------
| app/Providers/AppServiceProvider.php *** Copyright netprogs.pl | available only at Udemy.com | further distribution is prohibited  ***
|--------------------------------------------------------------------------
*/

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; /* Lecture 16 */

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        View::composer('backend.*', '\App\Booking\ViewComposers\BackendComposer');

        /* Lecture 16 */
        View::composer('frontend.*', function ($view) {
            $view->with('placeholder', asset('images/placeholder.jpg'));
        });

        if (App::environment('local'))
        {

            View::composer('*', function ($view) {
                $view->with('novalidate', 'novalidate');
            });

        }
        else
        {
            View::composer('*', function ($view) {
                $view->with('novalidate', null);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (App::environment('local'))
        {
            $this->app->bind(\App\Booking\Interfaces\FrontendRepositoryInterface::class,function()
            {
                return new \App\Booking\Repositories\FrontendRepository;
            });
        }
        else
        {
            $this->app->bind(\App\Booking\Interfaces\FrontendRepositoryInterface::class,function()
            {
                return new \App\Booking\Repositories\CachedFrontendRepository();
            });
        }


        $this->app->bind(\App\Booking\Interfaces\BackendRepositoryInterface::class,function()
        {
            return new \App\Booking\Repositories\BackendRepository;
        });
    }
}

