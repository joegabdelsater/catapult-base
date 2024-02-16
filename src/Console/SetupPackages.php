<?php

namespace Joegabdelsater\CatapultBase\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class SetupPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catapult:setup-packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the packages added through Catapult';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up packages...');

        $process = Process::run('composer run-script catapult-post-install-cmd');
        $this->info($process->output());
        $this->info('Packages setup successfully.');
    }
}
