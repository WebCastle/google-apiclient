<?php

namespace PulkitJalan\Google;

use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $shortName = 'googleclient';

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->app['PulkitJalan\Google\Client'] = function ($app) {
            return $app['google.api.client'];
        };
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app             = $this->app ?: app();
        $laravel_version = substr($app::VERSION, 0, strpos($app::VERSION, '.'));

        if ($laravel_version == 5) {
            $location = __DIR__ . '/../config/config.php';

            $this->mergeConfigFrom($location, $this->shortName);

            $this->publishes([
                $location => config_path($this->shortName . '.php'),
            ]);
        } else if ($laravel_version == 4) {
            $this->package('pulkitjalan/google-apiclient', realpath(__DIR__ . '/../config'), 'google');
        }

        $this->app['google.api.client'] = $this->app->share(function () {
            return new Client($this->app->config->get('google::config'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['google.api.client', 'PulkitJalan\Google\Client'];
    }
}
