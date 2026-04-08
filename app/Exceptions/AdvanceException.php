<?php

namespace App\Exceptions;

use Exception;

class AdvanceException extends Exception
{
    public static function alreadyCollected(): self
    {
        return new self('هذا الدفعة المقدمة تم تحصيلها بالكامل بالفعل');
    }

    public static function notFound(): self
    {
        return new self('لم يتم العثور على الدفعة المقدمة');
    }

    public static function invalidRecoveryMethod(): self
    {
        return new self('طريقة الاسترجاع غير صحيحة');
    }

    public static function invalidInstallmentCount(): self
    {
        return new self('عدد الأقساط يجب أن يكون أكبر من صفر');
    }
}
