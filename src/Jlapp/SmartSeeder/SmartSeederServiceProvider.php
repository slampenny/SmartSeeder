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
        App::bind('Jlapp\SmartSeeder\SmartSeederRepository', function($app) {
            return new SmartSeederRepository($app['db'], Config::get('smart-seeder::app.seedTable'));
        });

        App::bind('Jlapp\SmartSeeder\SeedMigrator', function($app)
        {
            //return $app->make('Jlapp\SmartSeeder\SeedMigrator', array());
            return new SeedMigrator($app['Jlapp\SmartSeeder\SmartSeederRepository'], $app['db'], $app['files']);
        });

        $this->app->bind('command.seed', 'Jlapp\SmartSeeder\SeedOverrideCommand');

        /*$this->app->bind('seed.migrator', function($app)
        {
            return new SeedMigrator($app['seed.repository'], $app['db'], $app['files']);
        });*/

       /*

        $this->app->bind('seed.run', function($app)
        {
            return new SeedCommand($app['seed.migrator']);
        });

        $this->app->bind('seed.install', function($app)
        {
            return new SeedInstallCommand($app['seed.repository']);
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
            return new SeedRollbackCommand($migrator);
        });

        $this->app->bind('seed.refresh', function()
        {
            return new SeedRefreshCommand();
        });*/

        $this->commands(array(
            'Jlapp\SmartSeeder\SeedCommand',
            'Jlapp\SmartSeeder\SeedInstallCommand',
            'Jlapp\SmartSeeder\SeedMakeCommand',
            'Jlapp\SmartSeeder\SeedResetCommand',
            'Jlapp\SmartSeeder\SeedRollbackCommand',
            'Jlapp\SmartSeeder\SeedRefreshCommand',
        ));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('Jlapp\SmartSeeder\SmartSeederRepository', 'Jlapp\SmartSeeder\SeedMigrator', 'command.seed');
    }

}
