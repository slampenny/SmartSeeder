<?php namespace Jlapp\SmartSeeder;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Facades\App;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Config;

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

}