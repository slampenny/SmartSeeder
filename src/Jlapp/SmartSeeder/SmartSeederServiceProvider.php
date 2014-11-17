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
    protected $defer = false;

    public function boot() {
        $this->package('jlapp/smart-seeder');

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

        $this->app->bindShared('command.seed', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedOverrideCommand($migrator);
        });
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('command.seed.run', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedCommand($migrator);
        });

        $this->app->bind('command.seed.install', function($app)
        {
            $repository = $app['seed.repository'];
            return new SeedInstallCommand($repository);
        });

        $this->app->bind('command.seed.make', function($app)
        {
            return new SeedMakeCommand();
        });

        $this->app->bind('command.seed.reset', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedResetCommand($migrator);
        });

        $this->app->bind('command.seed.rollback', function($app)
        {
            $migrator = $app['seed.migrator'];
            return new SeedResetCommand($migrator);
        });

        $this->app->bind('command.seed.refresh', function()
        {
            return new SeedRefreshCommand();
        });

        $this->commands(array('smart-seeder::command.seed.run', 'smart-seeder::command.seed.install', 'smart-seeder::command.seed.make', 'smart-seeder::command.seed.reset', 'smart-seeder::command.seed.rollback', 'smart-seeder::command.seed.refresh'));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('seed.repository', 'seed.migrator', 'command.seed.run', 'command.seed.install', 'command.seed.make', 'command.seed.reset', 'command.seed.rollback', 'command.seed.refresh', 'command.seed');
    }

}
