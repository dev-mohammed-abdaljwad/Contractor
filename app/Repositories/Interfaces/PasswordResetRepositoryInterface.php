<?php

namespace App\Repositories\Interfaces;

use App\Models\PasswordResetCode;

interface PasswordResetRepositoryInterface
{
    /**
     * Create a new password reset code for the given email.
     */
    public function createCode(string $email): PasswordResetCode;

    /**
     * Find a valid password reset code by email and code.
     */
    public function findValidCode(string $email, string $code): ?PasswordResetCode;

    /**
     * Delete all password reset codes for the given email.
     */
    public function deleteByEmail(string $email): void;

    /**
     * Increment the number of attempts for a reset code.
     */
    public function incrementAttempts(PasswordResetCode $resetCode): void;

    /**
     * Check if the reset code has exceeded the maximum number of attempts.
     */
    public function hasExceededAttempts(PasswordResetCode $resetCode): bool;
}
