<?php

namespace App\Exceptions;

use RuntimeException;

class SettingsException extends RuntimeException
{
    /**
     * Current password is incorrect.
     */
    public static function wrongCurrentPassword(): self
    {
        return new self('كلمة السر الحالية غير صحيحة');
    }

    /**
     * New password is same as current password.
     */
    public static function sameAsCurrentPassword(): self
    {
        return new self('كلمة السر الجديدة يجب أن تكون مختلفة عن الحالية');
    }

    /**
     * Email is already in use by another user.
     */
    public static function emailAlreadyTaken(): self
    {
        return new self('البريد الإلكتروني مستخدم بالفعل');
    }

    /**
     * User is not authorized to perform this action.
     */
    public static function unauthorized(): self
    {
        return new self('غير مصرح لك بتنفيذ هذا الإجراء');
    }
}
