<?php namespace Jlapp\SmartSeeder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SmartSeederServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot() {
        $this->package('jlapp/smart-seeder');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        App::bind('seed.repository', function($app) {
            return new SmartSeederRepository($app['db'], Config::get('smart-seeder::app.seedTable'));
        });

        App::bind('seed.migrator', function($app)
        {
            return new SeedMigrator($app['seed.repository'], $app['db'], $app['files']);
        });

        $this->app->bind('command.seed', function($app)
        {
            return new SeedCommand($app['seed.migrator']);
        });

        $this->app->bind('seed.run', function($app)
        {
            return new SeedCommand($app['seed.migrator']);
        });

        $this->app->bind('seed.install', function($app)
        {
            return new SeedInstallCommand($app['seed.repository']);
        });

        $this->app->bind('seed.make', function()
        {
            return new SeedMakeCommand();
        });

        $this->app->bind('seed.reset', function($app)
        {
            return new SeedResetCommand($app['seed.migrator']);
        });

        $this->app->bind('seed.rollback', function($app)
        {
            return new SeedRollbackCommand($app['seed.migrator']);
        });

        $this->app->bind('seed.refresh', function()
        {
            return new SeedRefreshCommand();
        });

        $this->commands(array(
            'seed.run',
            'seed.install',
            'seed.make',
            'seed.reset',
            'seed.rollback',
            'seed.refresh',
        ));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'seed.repository',
            'seed.migrator',
            'command.seed',
            'seed.run',
            'seed.install',
            'seed.make',
            'seed.reset',
            'seed.rollback',
            'seed.refresh',
        );
    }

}
