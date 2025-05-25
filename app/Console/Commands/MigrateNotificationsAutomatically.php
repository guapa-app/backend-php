<?php

namespace App\Console\Commands;

use App\Services\NotificationInterceptor;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MigrateNotificationsAutomatically extends Command
{
    protected $signature = 'notifications:auto-migrate {--dry-run : Show what would be changed without making changes} {--file= : Migrate specific file only}';
    protected $description = 'Automatically migrate old notification patterns to use the unified service';

    protected $patterns = [
        // Pattern: app(\App\Services\NotificationInterceptor::class)->interceptSingle($$user, $$notification)
        'single_notify' => [
            'pattern' => '/(\$\w+)->notify\(([^)]+)\)/',
            'replacement' => "app(\\App\\Services\\NotificationInterceptor::class)->interceptSingle($1, $2)"
        ],

        // Pattern: app(\App\Services\NotificationInterceptor::class)->interceptBulk($$users, $$notification)
        'bulk_send' => [
            'pattern' => '/Notification::send\(([^,]+),\s*([^)]+)\)/',
            'replacement' => "app(\\App\\Services\\NotificationInterceptor::class)->interceptBulk($1, $2)"
        ],

        // Pattern: // TODO: Handle routed notification manually: $$notification
        'route_notify' => [
            'pattern' => '/Notification::route\([^)]+\)->notify\(([^)]+)\)/',
            'replacement' => "// TODO: Handle routed notification manually: $1"
        ]
    ];

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $specificFile = $this->option('file');

        $this->info('🔄 Automatic Notification Migration');
        $this->info('================================');
        $this->newLine();

        if ($isDryRun) {
            $this->comment('🧪 DRY RUN MODE - No files will be modified');
            $this->newLine();
        }

        // Get files to process
        $files = $specificFile ? [$specificFile] : $this->getPhpFiles();

        $totalChanges = 0;
        $processedFiles = 0;

        foreach ($files as $file) {
            if (!File::exists($file)) {
                $this->error("File not found: {$file}");
                continue;
            }

            $changes = $this->processFile($file, $isDryRun);
            if ($changes > 0) {
                $processedFiles++;
                $totalChanges += $changes;
            }
        }

        $this->newLine();
        $this->info("📊 Migration Summary:");
        $this->line("   Files processed: {$processedFiles}");
        $this->line("   Total changes: {$totalChanges}");

        if ($isDryRun && $totalChanges > 0) {
            $this->newLine();
            $this->comment("💡 Run without --dry-run to apply changes");
        } elseif (!$isDryRun && $totalChanges > 0) {
            $this->newLine();
            $this->info("✅ Migration completed successfully!");
            $this->comment("🔍 Don't forget to add the NotificationInterceptor to your imports");
        }
    }

    protected function processFile(string $filePath, bool $isDryRun): int
    {
        $content = File::get($filePath);
        $originalContent = $content;
        $changes = 0;

        foreach ($this->patterns as $patternName => $config) {
            $newContent = preg_replace_callback(
                $config['pattern'],
                function ($matches) use ($config, $filePath, &$changes) {
                    $changes++;
                    $this->line("   🔄 Found {$config['pattern']} in {$filePath}");
                    return str_replace(
                        array_keys($matches),
                        array_values($matches),
                        $config['replacement']
                    );
                },
                $content
            );

            if ($newContent !== null) {
                $content = $newContent;
            }
        }

        // Only write if changes were made and not in dry-run mode
        if ($changes > 0 && !$isDryRun) {
            // Add import for NotificationInterceptor if not present
            $content = $this->addImportIfNeeded($content);
            File::put($filePath, $content);
            $this->info("   ✅ Updated {$filePath} ({$changes} changes)");
        } elseif ($changes > 0) {
            $this->comment("   📝 Would update {$filePath} ({$changes} changes)");
        }

        return $changes;
    }

    protected function addImportIfNeeded(string $content): string
    {
        // Check if NotificationInterceptor import already exists
        if (strpos($content, 'use App\\Services\\NotificationInterceptor;') !== false) {
            return $content;
        }

        // Find the namespace declaration
        if (preg_match('/namespace\s+[^;]+;/', $content, $matches)) {
            $namespaceDeclaration = $matches[0];

            // Add import after namespace
            $import = "\n\nuse App\\Services\\NotificationInterceptor;";
            $content = str_replace(
                $namespaceDeclaration,
                $namespaceDeclaration . $import,
                $content
            );
        }

        return $content;
    }

    protected function getPhpFiles(): array
    {
        $files = [];

        // Define directories to scan
        $directories = [
            'app/Services',
            'app/Http/Controllers',
            'app/Jobs',
            'app/Listeners',
            'app/Nova/Actions',
            'app/Filament',
            'app/Console/Commands',
        ];

        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                $phpFiles = File::allFiles($directory);
                foreach ($phpFiles as $file) {
                    if ($file->getExtension() === 'php') {
                        $files[] = $file->getPathname();
                    }
                }
            }
        }

        return $files;
    }
}
