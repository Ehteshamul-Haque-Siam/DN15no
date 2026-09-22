<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature   = 'db:backup';
    protected $description = 'Backup MySQL database to storage/app/backups';

    public function handle(): int
    {
        $db = config('database.connections.mysql');

        $filename = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path     = storage_path("app/backups/{$filename}");

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $cmd = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --single-transaction %s > %s',
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['host']),
            escapeshellarg($db['database']),
            escapeshellarg($path)
        );

        exec($cmd, $out, $code);

        if ($code !== 0 || !file_exists($path)) {
            $this->error('Backup failed.');
            return self::FAILURE;
        }

        // Delete backups older than 30 days
        foreach (glob(storage_path('app/backups/*.sql')) as $old) {
            if (filemtime($old) < now()->subDays(30)->timestamp) {
                @unlink($old);
            }
        }

        $this->info("Backup saved: {$filename} (" . round(filesize($path) / 1024) . ' KB)');
        return self::SUCCESS;
    }
}