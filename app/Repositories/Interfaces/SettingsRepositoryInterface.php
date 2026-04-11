<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\UserPreference;

interface SettingsRepositoryInterface
{
    /**
     * Update user profile information.
     */
    public function updateProfile(User $user, array $data): User;

    /**
     * Update user password.
     */
    public function updatePassword(User $user, string $newPassword): bool;

    /**
     * Get user preferences, creating defaults if not found.
     */
    public function getPreferences(int $userId): UserPreference;

    /**
     * Update user preferences.
     */
    public function updatePreferences(int $userId, array $data): UserPreference;

    /**
     * Terminate all other user sessions.
     */
    public function terminateOtherSessions(int $userId, string $currentSessionId): void;
}
