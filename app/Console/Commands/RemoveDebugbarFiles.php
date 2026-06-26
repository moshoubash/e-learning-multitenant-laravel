<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveDebugbarFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debugbar:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up debugbar files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = storage_path('debugbar');

        if (\Illuminate\Support\Facades\File::exists($path)) {
            \Illuminate\Support\Facades\File::cleanDirectory($path);
        }
    }
}
