<?php

namespace Jlapp\SmartSeeder;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\ConfirmableTrait;

use App;
use File;

class SeedResetCommand extends Command {

    use ConfirmableTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seed:reset';

    private $migrator;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resets all the seeds in the database';


    public function __construct(SeedMigrator $migrator) {
        parent::__construct();
        $this->migrator = $migrator;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        if ( ! $this->confirmToProceed()) return;

        $this->prepareDatabase();

        $env = $this->option('env');

        if (File::exists(database_path(config('smart-seeder.seedsDir')))) {
            $this->migrator->setEnv($env);
        }
        //otherwise use the default environment

        $this->migrator->setConnection($this->input->getOption('database'));

        $pretend = $this->input->getOption('pretend');

        while (true)
        {
            $count = $this->migrator->rollback($pretend);

            // Once the migrator has run we will grab the note output and send it out to
            // the console screen, since the migrator itself functions without having
            // any instances of the OutputInterface contract passed into the class.
            foreach ($this->migrator->getNotes() as $note)
            {
                $this->output->writeln($note);
            }

            if ($count == 0) break;
        }

        $this->line("Seeds reset for $env");
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        $this->migrator->setConnection($this->input->getOption('database'));

        if ( ! $this->migrator->repositoryExists())
        {
            $options = array('--database' => $this->input->getOption('database'));

            $this->call('seed:install', $options);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    /*protected function getArguments()
    {
        return array(
            array('from', InputArgument::REQUIRED, 'The from date in the format dd-mm-yyyy.'),
            array('to', InputArgument::REQUIRED, 'The to date in the format dd-mm-yyyy.'),
        );
    }*/

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('env', null, InputOption::VALUE_OPTIONAL, 'The environment in which to run the seeds.', null),

            array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'),

            array('force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'),

            array('pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.'),
        );
    }
}