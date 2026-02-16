<?php

namespace Am112\C2pay\Commands;

use Am112\C2pay\Models\C2payLogger;
use Illuminate\Console\Command;

class C2payLoggerCleanupCommand extends Command
{
    public $signature = 'c2pay:cleanup-logger';

    public $description = 'Cleans up the C2pay logger by deleting log files older than 30 days.';

    public function handle(): int
    {
        $this->comment('Cleaning up C2pay logger...');

        C2payLogger::where('created_at', '<', now()->subDays(30))->delete();

        $this->comment('All done');

        return self::SUCCESS;
    }
}