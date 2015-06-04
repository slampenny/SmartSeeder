<?php namespace Jlapp\SmartSeeder;

use Symfony\Component\Console\Input\InputOption;

class SeedOverrideCommand extends SeedCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:seed';

    public function __construct(SeedMigrator $migrator) {
        parent::__construct($migrator);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options[] = array('class', null, InputOption::VALUE_OPTIONAL, "There is no rootseeder in the SmartSeeder package, but we need this to override Laravel's behaviour.", null);
        return $options;
    }

}