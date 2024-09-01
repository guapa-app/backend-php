<?php

namespace App\Jobs;

use App\Imports\ClientsImport;
use App\Services\VendorClientService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProcessClientImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $vendor;

    public function __construct($filePath, $vendor)
    {
        $this->filePath = $filePath;
        $this->vendor = $vendor;
    }

    public function handle(VendorClientService $vendorClientService)
    {
        $import = new ClientsImport();
        Excel::import($import, $this->filePath);

        $successCount = 0;
        $failureCount = count($import->getFailures());

        foreach ($import->toArray($this->filePath)[0] as $row) {
            try {
                $vendorClientService->addClient($this->vendor, $row);
                $successCount++;
            } catch (\Exception $e) {
                $failureCount++;
            }
        }
        \Log::info("Import completed. Successes: $successCount, Failures: $failureCount");

        // Clean up the temporary file
        unlink($this->filePath);
    }
}
