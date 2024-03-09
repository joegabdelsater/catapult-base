<?php

namespace Joeabdelsater\CatapultBase\Console;

use Illuminate\Console\Command;

class TranslateFilamentServiceProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catapult:translate-filament-service-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the translatable requirements to the filament AdminServiceProvider';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = config('directories.filament_service_provider_file');
        $importsCode = implode("\n", config('packages.filament_translatable.service_provider.imports'));
        $chainMethods = implode("\n", config('packages.filament_translatable.service_provider.chained_methods.chain'));

        $fileContent = file_get_contents($filePath);

        $pattern = '/(use [^;]+;)$/m';
        $replacement = "$1\n$importsCode";

        $modifiedContents = preg_replace($pattern, $replacement, $fileContent, 1);
        

    ;
        $replacement = 'return $panel' . $chainMethods;

        $modifiedContents = preg_replace('/return \$panel/m', $replacement, $modifiedContents, 1);

        file_put_contents($filePath, $modifiedContents);
    }
}
