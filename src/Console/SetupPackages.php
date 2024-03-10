<?php

namespace Joeabdelsater\CatapultBase\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Joeabdelsater\CatapultBase\Models\CatapultPackage;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

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

        $process = Process::run('composer update -w');

        $this->info($process->output());

        if (CatapultPackage::where('package_key', 'filament')->exists()) {
            // $this->info('installing filament and creating admin user');
            // $process = Process::run('php artisan filament:install --panels --force');
            // $this->info($process->output());


            $this->info('Creating admin user');

            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ]);

            $this->info('Admin user created');
            $this->info('admin@admin.com / password');
        }


        $this->info($process->output());
        $this->info('Packages setup successfully.');
    }
}
