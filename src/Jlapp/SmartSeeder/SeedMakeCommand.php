<?php namespace Jlapp\SmartSeeder;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class SeedMakeCommand extends Command {

    use AppNamespaceDetectorTrait;

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
        $path = base_path(config('smart-seeder.seedDir'));

        $env = $this->option('env');
        if (!empty($env)) {
            $path .= "/$env";
        }

        if (!File::exists($path)) {
            File::makeDirectory($path);
        }
        $created = date('Y_m_d_His');
        $path .= "/seed_{$created}_{$model}Seeder.php";

        $fs = File::get(__DIR__."/stubs/DatabaseSeeder.stub");

        $stub = str_replace('{{model}}', "seed_{$created}_".$model.'Seeder', $fs);
        $stub = str_replace('{{namespace}}', " namespace $namespace;", $stub);
        File::put($path, $stub);

        $message = "Seed created for $model";
        if (!empty($env)) {
            $message .= " in environment: $env";
        }

        $this->line($message);
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