<?php namespace Jlapp\SmartSeeder;

use Illuminate\Database\Migrations\MigrationCreator;

class SeedMigrationCreator extends MigrationCreator {
    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__.'/stubs';
    }
} 