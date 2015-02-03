# SmartSeeder for Laravel

Seeding as it is currently done in Laravel is intended only for dev builds, but what if you want to seed a production database with different data from what you use in development? What if you want to seed a table you've added to a database that is currently in production with new data?

Features
========

- Allows you to seed databases in different environments with different values.
- Allows you to "version" seeds the same way that Laravel currently handles migrations. Running ```php artisan seed``` will only run seeds that haven't already been run.
- Prompts you if your database is in production.
- Allows you to run multiple seeds of the same model/table
- Overrides Laravel's seeding commands. SmartSeeder will fire when you run
    ```
    php artisan db:seed
    ```
     or
    ```
    php artisan migrate:refresh --seed
    ```

Use
=====
When you install SmartSeeder, various artisan commands are made available to you which use the same methodology you're used to using with Migrations.

<table>
<tr><td>seed:run</td><td>Runs all the seeds in the smartSeeds directory that haven't been run yet.</td></tr>
<tr><td>seed:make</td><td>Makes a new seed class in the environment you specify.</td></tr>
<tr><td>seed:rollback</td><td>Rollback doesn't undo seeding (which would be impossible with an auto-incrementing primary key). It just allows you to re-run the last batch of seeds.</td></tr>
<tr><td>seed:reset</td><td>Resets all the seeds.</td></tr>
<tr><td>seed:refresh</td><td>Resets and re-runs all seeds.</td></tr>
<tr><td>seed:install</td><td>You don't have to use this... it will be run automatically when you call "seed"</td></tr>
</table>

Installation
============

- Add require: "jlapp/smart-seeder": "dev-master" to your composer.json and run an update to bring it in.
- Add 'Jlapp\SmartSeeder\SmartSeederServiceProvider' to your providers array in app/config/app.php
- Run php artisan config:publish jlapp/smart-seeder to push config files to your app folder if you want to override the name of the seeds folder or the name of the table where seeds are stored