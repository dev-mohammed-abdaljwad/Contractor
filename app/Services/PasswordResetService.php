<?php

namespace App\Services;

use App\Exceptions\PasswordResetException;
use App\Mail\PasswordResetCodeMail;
use App\Models\PasswordResetCode;
use App\Models\User;
use App\Repositories\Interfaces\PasswordResetRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PasswordResetService
{
    public function __construct(
        protected PasswordResetRepositoryInterface $repository
    ) {}

    /**
     * Send a password reset code to the user's email.
     *
     * @throws PasswordResetException
     */
    public function sendCode(string $email): void
    {
        DB::transaction(function () use ($email) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                // To avoid email enumeration, we don't throw an error here in the controller,
                // but we need to stop the process.
                throw PasswordResetException::emailNotFound();
            }

            $resetCode = $this->repository->createCode($email);

            try {
                Mail::to($email)->send(new PasswordResetCodeMail($resetCode->code, $user->name));
            } catch (\Exception $e) {
                Log::error('Mail failed: ' . $e->getMessage());
                throw PasswordResetException::mailFailed();
            }
        });
    }

    /**
     * Verify the reset code and return a signed token.
     *
     * @throws PasswordResetException
     */
    public function verifyCode(string $email, string $code): string
    {
        return DB::transaction(function () use ($email, $code) {
            $resetCode = $this->repository->findValidCode($email, $code);

            if (!$resetCode) {
                // If code is wrong, increment attempts for any existing valid code for this email
                $anyValidCode = PasswordResetCode::query()
                    ->where('email', $email)
                    ->whereNull('used_at')
                    ->where('expires_at', '>', now())
                    ->first();

                if ($anyValidCode) {
                    $this->repository->incrementAttempts($anyValidCode);
                    if ($this->repository->hasExceededAttempts($anyValidCode)) {
                        throw PasswordResetException::tooManyAttempts();
                    }
                }

                throw PasswordResetException::invalidCode();
            }

            if ($resetCode->isExpired()) {
                throw PasswordResetException::expiredCode();
            }

            $resetCode->markAsUsed();

            $token = hash('sha256', $email . $code . config('app.key'));
            Cache::put('pw_reset_' . $token, $email, 900); // 15 minutes

            return $token;
        });
    }

    /**
     * Reset the user's password using the signed token.
     *
     * @throws PasswordResetException
     */
    public function resetPassword(string $token, string $newPassword): void
    {
        DB::transaction(function () use ($token, $newPassword) {
            $email = Cache::get('pw_reset_' . $token);

            if (!$email) {
                throw PasswordResetException::invalidToken();
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                throw PasswordResetException::emailNotFound();
            }

            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            Cache::forget('pw_reset_' . $token);

            // Logout all other sessions
            DB::table('sessions')->where('user_id', $user->id)->delete();
        });
    }
}
