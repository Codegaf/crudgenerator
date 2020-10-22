<?php

namespace Codegaf\Crudgenerator;

use Illuminate\Support\ServiceProvider;

class CrudgeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'crudgenerator');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'crudgenerator');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            /*
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('crudgenerator.php'),
            ], 'config');
            */

            $this->publishes([
                __DIR__.'/stubs/custom' => base_path('/stubs/custom'),
            ], 'stubs');

            $this->publishes([
                __DIR__.'/stubs/CrudGenerator.stub' => app_path('Console/Commands/CrudGenerator.php'),
                __DIR__.'/stubs/DestroyCrud.stub' => app_path('Console/Commands/DestroyCrud.php')
            ], 'commands');

            $this->commands([
                CrudGenerator::class,
                DestroyCrud::class
            ]);

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/crudgenerator'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/crudgenerator'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/crudgenerator'),
            ], 'lang');*/
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        //$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'crudgenerator');

        // Register the main class to use with the facade
        $this->app->singleton('crudgenerator', function () {
            return new Crudgenerator;
        });
    }
}
