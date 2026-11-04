<?php

namespace App\Console\Commands;

use App\Models\Audit;
use Illuminate\Console\Command;

/**
 * Deletes visit records older than the retention period (default 12 months).
 * Runs on every deploy.
 */
class PruneVisits extends Command
{
    protected $signature = 'app:prune-visits {--days=365 : Keep visits from this many days}';

    protected $description = 'Delete visit records older than the retention period';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $deleted = Audit::where('created_at', '<', now()->subDays($days))->delete();
        $this->line("app:prune-visits: deleted {$deleted} visits older than {$days} days.");

        return self::SUCCESS;
    }
}
