<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteModelsPercentage extends Command
{
    protected $signature = 'files:delete-random 
                            {models=70} 
                            {controllers=70} 
                            {traits=70} 
                            {views=100}'; // Default: 70% models, 70% controllers, 70% traits, 100% view folders

    protected $description = 'Delete a percentage of model, controller, and trait files, and remove a random view folder';

    public function handle()
    {
        $this->deleteFiles('Models', (int) $this->argument('models'));
        $this->deleteFiles('Http/Controllers', (int) $this->argument('controllers'));
        $this->deleteFiles('Traits', (int) $this->argument('traits'));
        $this->deleteRandomFolder('resources/views', (int) $this->argument('views'));

        $this->info('Deletion process completed.');
    }

    /**
     * Delete a percentage of files in a given folder.
     */
    private function deleteFiles($folder, $percentage)
    {
        $folderPath = app_path($folder);

        if (!File::exists($folderPath)) {
            $this->error("The app/$folder directory does not exist.");
            return;
        }

        $files = File::files($folderPath);

        if (empty($files)) {
            $this->error("No files found in app/$folder.");
            return;
        }

        if ($percentage < 1 || $percentage > 100) {
            $this->error("Invalid percentage for $folder. Enter a value between 1 and 100.");
            return;
        }

        $numToDelete = (int) round(count($files) * ($percentage / 100));

        if ($numToDelete == 0) {
            $this->info("No files deleted in app/$folder, percentage too low.");
            return;
        }

        shuffle($files);
        $filesToDelete = array_slice($files, 0, $numToDelete);

        foreach ($filesToDelete as $file) {
            File::delete($file);
            $this->info("Deleted: " . $file->getFilename() . " from $folder");
        }

        $this->info("Deleted $numToDelete files from app/$folder.");
    }

    /**
     * Delete a random folder inside the given directory.
     */
    private function deleteRandomFolder($folderPath, $percentage)
    {
        if (!File::exists(base_path($folderPath))) {
            $this->error("$folderPath does not exist.");
            return;
        }

        $folders = File::directories(base_path($folderPath));

        if (empty($folders)) {
            $this->error("No folders found in $folderPath.");
            return;
        }

        // Calculate how many folders to delete based on percentage
        $numToDelete = (int) round(count($folders) * ($percentage / 100));

        if ($numToDelete == 0) {
            $this->info("No folders deleted in $folderPath, percentage too low.");
            return;
        }

        shuffle($folders);
        $foldersToDelete = array_slice($folders, 0, $numToDelete);

        foreach ($foldersToDelete as $folder) {
            File::deleteDirectory($folder);
            $this->info("Deleted folder: " . basename($folder) . " from $folderPath");
        }

        $this->info("Deleted $numToDelete folders from $folderPath.");
    }
}
