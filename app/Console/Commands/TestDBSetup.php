<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class TestDBSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:db-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DB Setup for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbname = $this->createTestingEnv();

        $driver = config('database.default');
        config(['database.connections.' . $driver . '.database' => $dbname]);

        $db = DB::connection()->getPdo();

        // INFO: This query only works with pgsql
        $exist = $db->query("SELECT datname FROM pg_database WHERE datname = '$dbname'")->rowCount() > 0;

        if (!$exist) {
            $confirm = $this->confirm("'$dbname' doesn't exist. Do you want to create it?", true);

            if (!$confirm) {
                return self::SUCCESS;
            }

            $db->exec("CREATE DATABASE $dbname");
            $this->info("✓ DATABASE '$dbname' created successfully.");
            $this->newLine();

            $this->comment('Running `php artisan migrate --env=testing --force` ...');
            $migrateCmd = 'migrate';
        } else {
            $this->info("✓ DATABASE '$dbname' already exists.");

            $this->newLine();

            $this->comment('Running `php artisan migrate:refresh --env=testing --force` ...');
            $migrateCmd = 'migrate:refresh';
        }

        $process = new Process(['php', 'artisan', $migrateCmd, '--force']);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            $this->line($buffer);
        });

        $this->info('✓ Finished migration.');

        $this->newLine();

        $this->comment('Running `php artisan db:seed --env=testing --force` ...');

        $process = new Process(['php', 'artisan', 'db:seed', '--force']);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            $this->line($buffer);
        });

        $this->info('✓ Finished seeding.');
        $this->newLine();

        $this->info('✓ Finished db setup for unit testing.');

        return self::SUCCESS;
    }

    private function createTestingEnv()
    {
        exec("@php -r \"file_exists('.env.testing') || copy('.env', '.env.testing');\"");

        $db = '';
        $lines = file('.env.testing');

        foreach ($lines as &$line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_starts_with($line, 'APP_ENV')) {
                $line = 'APP_ENV=testing';
            }

            if (str_starts_with($line, 'DB_DATABASE')) {
                [$var, $value] = explode('=', $line);

                if (!str_ends_with($line, 'test')) {
                    $db = $value = rtrim($value, '_') . '_test';
                    $line = $var . '=' . $value; // appending with 'test' only when value not ending with 'test'
                } else {
                    $db = $value;
                }
            }
        }

        file_put_contents('.env.testing', implode(PHP_EOL, $lines) . PHP_EOL);

        $this->info("✓ .env.testing created successfully.");
        $this->newLine();

        return $db;
    }
}
