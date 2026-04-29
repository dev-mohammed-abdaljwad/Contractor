<?php

namespace App\Exceptions;

use Exception;

class PasswordResetException extends Exception
{
    public static function emailNotFound(): self
    {
        return new self('البريد الإلكتروني غير مسجل');
    }

    public static function invalidCode(): self
    {
        return new self('الكود غير صحيح');
    }

    public static function expiredCode(): self
    {
        return new self('انتهت صلاحية الكود — اطلب كود جديد');
    }

    public static function tooManyAttempts(): self
    {
        return new self('تجاوزت عدد المحاولات — اطلب كود جديد');
    }

    public static function invalidToken(): self
    {
        return new self('انتهت الجلسة — ابدأ من جديد');
    }

    public static function mailFailed(): self
    {
        return new self('حدث خطأ في إرسال الإيميل — حاول مرة أخرى');
    }
}
