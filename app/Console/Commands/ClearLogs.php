<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';
    protected $description = 'Clear Laravel log files';

    public function handle()
    {
        $logPath = storage_path('logs');
        $files = File::allFiles($logPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                File::delete($file->getPathname());
                $this->info("Deleted: {$file->getFilename()}");
            }
        }

        $this->info('All log files cleared successfully!');
        return 0;
    }
}
