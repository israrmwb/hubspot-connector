<?php

namespace IsrarMWB\HubspotConnector;

use Illuminate\Support\ServiceProvider;

class HubspotConnectorServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        // Merge the default config with the application's config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/hubspot-sync.php',
            'hubspot-sync'
        );
        
        $this->app->singleton('hubspot.client', function () {
            return new \IsrarMWB\HubspotConnector\Services\HubspotClient();
        });
        
    }

    /**
     * Bootstrap services (after all service providers are registered).
     */
    public function boot()
    {
        // Allow the config to be published to the Laravel app
        $this->publishes([
            __DIR__ . '/../config/hubspot-sync.php' => config_path('hubspot-sync.php'),
        ], 'hubspot-config');

        // Register artisan commands if the app is running in console
        if ($this->app->runningInConsole()) {
            $this->commands([
                \IsrarMWB\HubspotConnector\Commands\SyncHubspotData::class,
            ]);
        }
    }
}
