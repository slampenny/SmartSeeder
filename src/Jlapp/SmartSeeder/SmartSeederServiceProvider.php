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
        $this->app->bind('seed.repository', function($app)
        {
            $table = Config::get('smart-seeder::app.seedTable');

            return new SmartSeederRepository($app['db'], $table);
        });

        $this->app->bind('seed.migrator', function($app)
        {
            $repository = $app['seed.repository'];
            return new SeedMigrator($repository, $app['db'], $app['files']);
        });

        $this->app->bind('seed', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedCommand($migrator);
        });

        $this->app->bind('seed.install', function($app)
        {
            $repository = $app['seed.repository'];
            return new SeedInstallCommand($repository);
        });

        $this->app->bind('seed.make', function($app)
        {
            return new SeedMakeCommand();
        });

        $this->app->bind('seed.reset', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedResetCommand($migrator);
        });

        $this->app->bind('seed.rollback', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedResetCommand($migrator);
        });

        $this->app->bind('seed.refresh', function()
        {
            return new SeedRefreshCommand();
        });

        $this->app->bindShared('command.seed', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedOverrideCommand($migrator);
        });

        $this->commands(array('seed', 'seed.install', 'seed.make', 'seed.reset', 'seed.rollback', 'seed.refresh'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('seed', 'seed.install', 'seed.make', 'seed.reset', 'seed.rollback', 'seed.refresh', 'command.seed');
    }

}
