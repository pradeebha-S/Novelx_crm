<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\Support;

class BasicController extends Controller
{

    public function logs()
    {
        $file = storage_path('logs/laravel.log');
        if (!file_exists($file)) {
            return response()->json(['error' => 'Log file not found'], 404);
        }

        $lines = [];
        $fp    = fopen($file, "r");
        if ($fp) {
            fseek($fp, -1, SEEK_END);
            $buffer = '';
            while (ftell($fp) > 0) {
                $char = fgetc($fp);
                if ($char === "\n") {
                    $lines[] = strrev($buffer);
                    $buffer  = '';
                    if (count($lines) >= 100) break;
                } else {
                    $buffer .= $char;
                }
                fseek($fp, -2, SEEK_CUR);
            }
            fclose($fp);
        }
        return response()->json(array_reverse($lines), 200, [], JSON_PRETTY_PRINT);
    }

    public function logs_clear()
    {
        try {
            $file = storage_path('logs/laravel.log');
            if (file_exists($file)) {
                file_put_contents($file, '');
                Log::info("🧹 Laravel logs cleared successfully.");
                return response()->json(['status' => true, 'message' => 'Logs cleared successfully.']);
            }

            return response()->json(['status' => false, 'message' => 'Log file not found.'], 404);
        } catch (\Exception $e) {
            Log::error("❌ Failed to clear logs", ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to clear logs.']);
        }
    }

    public function logs_replace()
    {
        try {
            $logPath   = storage_path('logs/laravel.log');
            $logFolder = storage_path('logs');
            if (!file_exists($logPath)) {
                return response()->json(['status' => false, 'message' => 'Log file not found.'], 404);
            }

            if (!is_dir($logFolder)) {
                mkdir($logFolder, 0755, true);
            }

            $date    = now()->format('Y-m-d_H-i-s');
            $files   = glob($logFolder . "/laravel_{$date}_*.log");
            $count   = $files ? count($files) + 1 : 1;
            $newName = $logFolder . "/laravel_{$date}_{$count}.log";
            rename($logPath, $newName);
            file_put_contents($logPath, '');

            Log::info("🔄 Laravel log rotated → {$newName}");

            return response()->json([
                'status'  => true,
                'message' => "Log file rotated successfully.",
                'new_log' => basename($newName)
            ]);
        } catch (\Exception $e) {
            Log::error("❌ Log rotation failed", ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Log rotation failed.']);
        }
    }

    public function checkTime()
    {
        $now = Carbon::now();


        return response()->json([
            'current_time' => $now->format('d-m-Y h:i A'),
            'timezone'     => config('app.timezone'),
        ]);
    }

    public function optimizeAndAnalyzeTables()
    {
        $currentHour = Carbon::now()->format('H');
        try {
            $tables   = DB::select('SHOW TABLES');
            $dbName   = env('DB_DATABASE');
            $tableKey = "Tables_in_{$dbName}";
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                Log::info("Optimizing table: {$tableName}");
                DB::statement("OPTIMIZE TABLE {$tableName}");
                Log::info("Analyzing table: {$tableName}");
                DB::statement("ANALYZE TABLE {$tableName}");
            }
            Log::info("Database optimization and analysis completed successfully.");
            return response()->json(['message' => 'Database optimization and analysis completed successfully'], 200);
        } catch (\Exception $e) {
            Log::info("Error optimizing/analyzing tables: " . $e->getMessage());
            return response()->json(['error' => 'Database optimization failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function file_db_backup()
    {
        try {
            Log::info("📦 Backup process started");
            // $this->backupProject();
            $this->backupDatabase();
            Log::info("✅ Project and Database backup completed successfully.");
            return [
                'status'  => true,
                'message' => "✅ Project and Database backup created successfully.",
            ];
        } catch (\Exception $e) {
            Log::error("❌ Backup Failed: " . $e->getMessage());
            return [
                'status'  => false,
                'message' => "❌ Backup failed: " . $e->getMessage(),
            ];
        }
    }

    private function backupProject()
    {
        Log::info("🔹 Project backup started");
        $projectPath = base_path();
        $folder      = 'backups';
        $filename    = "project_backup_latest.zip";
        $backupPath  = storage_path("app/{$folder}/{$filename}");
        Storage::makeDirectory($folder);
        if (file_exists($backupPath)) {
            unlink($backupPath);
        }
        $zip = new ZipArchive;
        if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($projectPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($projectPath) + 1);
                    if ($relativePath === "{$folder}/{$filename}") {
                        continue;
                    }
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        } else {
            throw new \Exception("Unable to create project zip file.");
        }
        Log::info("✅ Project backup completed");
    }

    private function backupDatabase()
    {
        Log::info("🔹 Database backup started");
        $db       = env('DB_DATABASE');
        $user     = env('DB_USERNAME');
        $pass     = env('DB_PASSWORD');
        $host     = env('DB_HOST');
        $filename = storage_path("app/backups/db_backup_latest.sql");
        Storage::makeDirectory('backups');
        $files = Storage::files('backups');
        foreach ($files as $file) {
            if (str_ends_with($file, '.sql')) {
                Storage::delete($file);
            }
        }
        $command = "mysqldump -h {$host} -u {$user} -p\"{$pass}\" {$db} > {$filename}";
        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            throw new \Exception("Database backup failed (mysqldump error).");
        }
        Log::info("✅ Database backup completed");
    }

    public function clear_cache()
    {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize');
        return response()->json([
            'status'  => 'success',
            'message' => 'All caches cleared and optimized successfully!'
        ]);
    }

    public function migrate()
    {
        Artisan::call('migrate', ['--force' => true]);
        return response()->json([
            'status'  => 'success',
            'command' => 'migrate',
            'output'  => Artisan::output()
        ]);
    }

    public function db_seed()
    {
        Artisan::call('db:seed', ['--force' => true]);
        return response()->json([
            'status'  => 'success',
            'command' => 'db:seed',
            'output'  => Artisan::output()
        ]);
    }
    public function privacy_policy()
    {
        return view('admin.privacy_policy');
    }

    public function terms_conditions()
    {
        return view('admin.terms_conditions');
    }
    public function delete_user_account()
    {
        return response()->json([
            'status'  => 'success',
            'message' => 'User account deleted successfully',
            'code'    => 200,
        ], 200);
    }
    public function support()
    {
        $support = Support::all();
        return view('admin.support', compact('support'));
    }
}
