<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteAllModels extends Command
{
    protected $signature = 'models:delete-all'; // Artisan command name
    protected $description = 'Delete all model files from the app/Models directory';

    public function handle()
    {
        $modelsPath = app_path('Models'); // Target "app/Models" directory

        // Confirm before deletion
        if ($this->confirm('Are you sure you want to delete all models? This action cannot be undone.')) {
            if (File::exists($modelsPath)) {
                File::deleteDirectory($modelsPath);
                $this->info('All models have been deleted.');
            } else {
                $this->error('The app/Models directory does not exist.');
            }
        } else {
            $this->info('Operation canceled.');
        }
    }
}
