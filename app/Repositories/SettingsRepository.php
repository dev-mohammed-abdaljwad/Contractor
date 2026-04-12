<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserPreference;
use App\Repositories\Interfaces\SettingsRepositoryInterface;

class SettingsRepository implements SettingsRepositoryInterface
{
    /**
     * Update user profile information.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    /**
     * Update user password.
     */
    public function updatePassword(User $user, string $newPassword): bool
    {
        return $user->update(['password' => $newPassword]);
    }

    /**
     * Get user preferences, creating defaults if not found.
     */
    public function getPreferences(int $userId): UserPreference
    {
        return UserPreference::firstOrCreate(
            ['user_id' => $userId],
            [
                'language' => 'ar',
                'currency' => 'EGP',
                'date_format' => 'DD/MM/YYYY',
                'week_start' => 'sunday',
                'dark_mode' => false,
                'notify_overdue_payments' => true,
                'notify_daily_distribution' => true,
                'notify_weekly_report' => false,
                'notify_pending_advances' => true,
            ]
        );
    }

    /**
     * Update user preferences.
     */
    public function updatePreferences(int $userId, array $data): UserPreference
    {
        return UserPreference::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    /**
     * Terminate all other user sessions.
     */
    public function terminateOtherSessions(int $userId, string $currentSessionId): void
    {
        \DB::table('sessions')
            ->where('user_id', $userId)
            ->where('id', '!=', $currentSessionId)
            ->delete();
    }
}
