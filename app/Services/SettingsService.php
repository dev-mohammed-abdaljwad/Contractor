<?php

namespace App\Services;

use App\Exceptions\SettingsException;
use App\Models\User;
use App\Models\UserPreference;
use App\Repositories\Interfaces\SettingsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsService
{
    public function __construct(
        private SettingsRepositoryInterface $repository
    ) {}

    /**
     * Update personal information for a user.
     */
    public function updatePersonalInfo(array $data, User $user): User
    {
        return DB::transaction(function () use ($data, $user) {
            return $this->repository->updateProfile($user, $data);
        });
    }

    /**
     * Change user password.
     */
    public function changePassword(array $data, User $user): void
    {
        DB::transaction(function () use ($data, $user) {
            // Verify current password
            if (!Hash::check($data['current_password'], $user->password)) {
                throw SettingsException::wrongCurrentPassword();
            }

            // Ensure new password is different from current
            if (Hash::check($data['new_password'], $user->password)) {
                throw SettingsException::sameAsCurrentPassword();
            }

            $this->repository->updatePassword($user, $data['new_password']);
        });
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(array $data, User $user): UserPreference
    {
        return DB::transaction(function () use ($data, $user) {
            // Extract only notification fields
            $notifications = collect($data)
                ->filter(fn($value, $key) => str_starts_with($key, 'notify_'))
                ->toArray();

            return $this->repository->updatePreferences($user->id, $notifications);
        });
    }

    /**
     * Update system preferences.
     */
    public function updateSystemPreferences(array $data, User $user): UserPreference
    {
        return DB::transaction(function () use ($data, $user) {
            $preferences = [
                'language' => $data['language'] ?? null,
                'currency' => $data['currency'] ?? null,
                'date_format' => $data['date_format'] ?? null,
                'week_start' => $data['week_start'] ?? null,
                'dark_mode' => $data['dark_mode'] ?? false,
            ];

            // Remove null values
            $preferences = array_filter($preferences, fn($v) => $v !== null);

            // Update language in session if changed
            if (isset($preferences['language'])) {
                session(['locale' => $preferences['language']]);
            }

            return $this->repository->updatePreferences($user->id, $preferences);
        });
    }

    /**
     * Terminate all other user sessions.
     */
    public function terminateOtherSessions(array $data, User $user): void
    {
        DB::transaction(function () use ($data, $user) {
            // Verify password
            if (!Hash::check($data['password'], $user->password)) {
                throw SettingsException::wrongCurrentPassword();
            }

            $currentSessionId = session()->getId();
            $this->repository->terminateOtherSessions($user->id, $currentSessionId);
        });
    }
}
