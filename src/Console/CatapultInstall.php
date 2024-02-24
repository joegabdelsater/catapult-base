<?php

namespace Joeabdelsater\CatapultBase\Console;

use Illuminate\Console\Command;
use Joeabdelsater\CatapultBase\Models\CatapultModel;

class CatapultInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catapult:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the Catapult package and publish the assets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Installing catapult ...');

        $this->call('vendor:publish', ['--provider' => 'Joeabdelsater\CatapultBase\CatapultBaseServiceProvider']);

        $this->info('Catapult installed successfully.');

        $this->info('Adding the user model to catapult');

        CatapultModel::UpdateOrCreate(["name" => 'User'],[
            'name' => 'User',
            'table_name' => 'users',
            'imports' => [
                '// use Illuminate\Contracts\Auth\MustVerifyEmail;',
                'use Illuminate\Database\Eloquent\Factories\HasFactory;',
                'use Illuminate\Foundation\Auth\User as Authenticatable;',
                'use Illuminate\Notifications\Notifiable;',
                'use Laravel\Sanctum\HasApiTokens;',
            ],

            'extends' => 'Authenticatable',

            'traits' => [
                'Use HasApiTokens, HasFactory, Notifiable;',
            ],
            'properties' => [
                "protected \$fillable = [
        'name',
        'email',
        'password',
    ];",
                
                "protected \$hidden = [
        'password',
        'remember_token',
    ];",
            
                "protected \$casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];",
            ],
        ]);

        $this->info('Install done!');
    }
}
