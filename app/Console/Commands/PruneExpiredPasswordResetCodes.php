<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PruneExpiredPasswordResetCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:prune-reset-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune expired and used password reset codes from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = DB::table('password_reset_codes')
            ->where('expires_at', '<', Carbon::now())
            ->orWhereNotNull('used_at')
            ->delete();

        $this->info("Successfully pruned {$count} password reset codes.");
    }
}
