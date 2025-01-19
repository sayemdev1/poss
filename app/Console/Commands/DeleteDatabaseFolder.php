<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteDatabaseFolder extends Command
{
    protected $signature = 'database:delete-folder'; // Command name
    protected $description = 'Delete the entire database folder';

    public function handle()
    {
        $folderPath = database_path(); // Gets the "database/" folder path

        // Safety confirmation before deletion
        if ($this->confirm('Are you sure you want to delete the entire database folder? This action cannot be undone.')) {
            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
                $this->info('Database folder deleted successfully.');
            } else {
                $this->error('Database folder does not exist.');
            }
        } else {
            $this->info('Operation canceled.');
        }
    }
}
