<?php

namespace REBELinBLUE\Deployer\Providers;

use Illuminate\Support\ServiceProvider;
use REBELinBLUE\Deployer\Github\LatestRelease;

/**
 * Service provider to register the LatestRelease class as a singleton.
 **/
class UpdateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Define a constant for the application version
        if (!defined('APP_VERSION')) {
            define('APP_VERSION', trim(file_get_contents(app_path('../VERSION'))));
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('deployer.update-check', function ($app) {
            $cache = $app['cache.store'];

            return new LatestRelease($cache);
        });

        $this->app->alias('deployer.update-check', LatestRelease::class);
    }
}
