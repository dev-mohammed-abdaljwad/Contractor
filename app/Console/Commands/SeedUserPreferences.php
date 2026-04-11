<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Console\Command;

class SeedUserPreferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-user-preferences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user preferences for all existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereDoesntHave('preferences')->get();
        
        foreach ($users as $user) {
            UserPreference::create([
                'user_id' => $user->id,
                'notify_overdue_payments' => true,
                'notify_daily_distribution' => true,
                'notify_weekly_report' => false,
                'notify_pending_advances' => true,
                'language' => 'ar',
                'currency' => 'EGP',
                'date_format' => 'DD/MM/YYYY',
                'week_start' => 'Sunday',
                'dark_mode' => false,
            ]);
        }
        
        $this->info("User preferences created for {$users->count()} users.");
    }
}
