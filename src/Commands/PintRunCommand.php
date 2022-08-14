<?php

namespace Devanox\Envato\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class PintRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pint:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the pint for formatting the code';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Run this in terminal to Formate the code:
        $process = new Process(['./vendor/bin/pint']);
        $process->run();

        return 0;
    }
}
