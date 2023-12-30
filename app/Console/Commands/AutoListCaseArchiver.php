<?php

namespace App\Console\Commands;

use App\Models\ListCase;
use Illuminate\Console\Command;

class AutoListCaseArchiver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list-case:archiver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive ListCase item after 3 months ago if not updated.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = ListCase::withoutGlobalScopes()->whereNotNull('user_id')->where('updated_at', '<', now()->subDays(90))->archived(false);
        $count = $items->count();
        $items->delete();
        $this->info("Archived $count items");
    }
}
