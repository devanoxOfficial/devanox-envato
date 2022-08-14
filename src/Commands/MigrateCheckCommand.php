<?php

namespace Devanox\Envato\Commands;

use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Collection;

class MigrateCheckCommand extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:check {--database= : The database connection to use.} {--path= : The path of migrations files to be executed.}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows pending migrations. Command exits with non zero code if there are migrations to run';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Create a new migration rollback command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->migrator = app('migrator');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->migrator->usingConnection($this->option('database'), function () {
            if (!$this->migrator->repositoryExists()) {
                $this->components->error('Migration table not found.');

                return 2;
            }

            $ran = $this->migrator->getRepository()->getRan();

            if (count($migrations = $this->getStatusFor($ran)) > 0) {
                $this->newLine();

                $this->components->twoColumnDetail('<fg=gray>Migration name</>', '<fg=gray>Status</>');

                $migrations->each(
                    fn ($migration) => $this->components->twoColumnDetail($migration[0], $migration[1])
                );

                $this->newLine();

                return 1;
            } else {
                $this->components->info('No migrations to run.');
                return 0;
            }
        });
    }

    /**
     * Gets ran migrations with repository check
     *
     * @return array
     */
    public function getRanMigrations()
    {
        if (!$this->migrator->repositoryExists()) {
            return [];
        }
        return $this->migrator->getRepository()->getRan();
    }

    /**
     * Get the status for the given run migrations.
     *
     * @param  array  $ran
     * @return \Illuminate\Support\Collection
     */
    protected function getStatusFor(array $ran)
    {
        return Collection::make($this->getAllMigrationFiles())
            ->filter(function ($migration) use ($ran) {
                return !in_array($this->migrator->getMigrationName($migration), $ran);
            })
            ->map(function ($migration) {
                $migrationName = $this->migrator->getMigrationName($migration);

                $status = '<fg=yellow;options=bold>Pending</>';

                return [$migrationName, $status];
            });
    }

    /**
     * Get an array of all of the migration files.
     *
     * @return array
     */
    protected function getAllMigrationFiles()
    {
        return $this->migrator->getMigrationFiles($this->getMigrationPaths());
    }
}
