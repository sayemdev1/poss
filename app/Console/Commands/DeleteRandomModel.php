<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteRandomModel extends Command
{
    protected $signature = 'model:delete-random'; // Command name
    protected $description = 'Delete a random model file from the app/Models directory';

    public function handle()
    {
        $modelsPath = app_path('Models'); // Target "app/Models" directory

        if (!File::exists($modelsPath)) {
            $this->error('The app/Models directory does not exist.');
            return;
        }

        $files = File::files($modelsPath); // Get all model files

        if (empty($files)) {
            $this->error('No model files found in app/Models.');
            return;
        }

        // Select a random file
        $randomFile = $files[array_rand($files)];

        // Confirm deletion
        if ($this->confirm('Are you sure you want to delete the model: ' . $randomFile->getFilename() . '?')) {
            File::delete($randomFile);
            $this->info('Deleted: ' . $randomFile->getFilename());
        } else {
            $this->info('Operation canceled.');
        }
    }
}
