<?php namespace Jlapp\SmartSeeder;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class SeedMakeCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seed:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a seed';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $model = ucfirst($this->argument('model'));
        $path = app_path(Config::get('smart-seeder::app.seedDir'));

        $env = $this->option('env');
        if (!empty($env)) {
            $path .= "/$env";
        }

        if (!File::exists($path)) {
            File::makeDirectory($path);
        }
        $path .= "/".date('Y_m_d_His')."_{$model}Seeder.php";

        $fs = File::get(__DIR__."/stubs/DatabaseSeeder.stub");

        $stub = str_replace('{{model}}', $model, $fs);
        File::put($path, $stub);

        $this->line("Seed created for $model in environment: $env");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('model', InputArgument::REQUIRED, 'The name of the model you wish to seed.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('env', null, InputOption::VALUE_OPTIONAL, 'The environment to seed to.', null),
        );
    }
}