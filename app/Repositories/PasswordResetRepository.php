<?php

namespace App\Repositories;

use App\Models\PasswordResetCode;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use Illuminate\Support\Str;

class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function createCode(string $email): PasswordResetCode
    {
        // Delete any existing codes for this email first
        $this->deleteByEmail($email);

        // Generate random 6-digit code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'attempts' => 0,
            'expires_at' => now()->addMinutes(15),
            'used_at' => null,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function findValidCode(string $email, string $code): ?PasswordResetCode
    {
        return PasswordResetCode::query()
            ->where('email', $email)
            ->where('code', $code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->where('attempts', '<', 3)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function deleteByEmail(string $email): void
    {
        PasswordResetCode::where('email', $email)->delete();
    }

    /**
     * @inheritDoc
     */
    public function incrementAttempts(PasswordResetCode $resetCode): void
    {
        $resetCode->increment('attempts');
    }

    /**
     * @inheritDoc
     */
    public function hasExceededAttempts(PasswordResetCode $resetCode): bool
    {
        return $resetCode->attempts >= 3;
    }
}
