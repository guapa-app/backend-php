<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateTelescopeReport extends Command
{
    protected $signature = 'telescope:generate-report';
    protected $description = 'Generate a Telescope statistics report';

    public function handle()
    {
        $totalRequests = DB::table('telescope_entries')
            ->where('type', 'request')
            ->count();

        $totalExceptions = DB::table('telescope_entries')
            ->where('type', 'exception')
            ->count();

        $requestsPerEndpoint = DB::table('telescope_entries')
            ->where('type', 'request')
            ->select('content->uri as endpoint', DB::raw('count(*) as count'))
            ->groupBy('content->uri')
            ->orderBy('count', 'desc')
            ->get();

        // Display the statistics
        $this->info("Total Requests: {$totalRequests}");
        $this->info("Total Exceptions: {$totalExceptions}");
        $this->info("Requests per Endpoint:");
        foreach ($requestsPerEndpoint as $request) {
            $this->warn("{$request->endpoint}: {$request->count}");
        }

        $this->tagsReport();
    }

    public function tagsReport()
    {
        // Group entries by tag
        $entriesGroupedByTag = DB::table('telescope_entries_tags')
            ->select('tag', DB::raw('count(*) as count'))
            ->groupBy('tag')
            ->orderBy('count', 'desc')
            ->get();

        $this->info("Telescope Tag Summary:");
        foreach ($entriesGroupedByTag as $group) {
            $this->warn("Tag: {$group->tag}, Count: {$group->count}");
        }
    }
}
