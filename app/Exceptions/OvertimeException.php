<?php

namespace App\Exceptions;

use RuntimeException;

class OvertimeException extends RuntimeException
{
    /**
     * Distribution not found exception
     */
    public static function distributionNotFound(): self
    {
        return new self('التوزيع غير موجود');
    }

    /**
     * Unauthorized access to distribution
     */
    public static function unauthorized(): self
    {
        return new self('لا توجد صلاحية للوصول لهذا التوزيع');
    }

    /**
     * Distribution is too old to edit (older than 7 days)
     */
    public static function tooOld(): self
    {
        return new self('لا يمكن تعديل سهر أيام أقدم من 7 أيام');
    }

    /**
     * Worker was not distributed on this day
     */
    public static function workerNotDistributed(): self
    {
        return new self('العامل لم يكن موزعاً في هذا اليوم');
    }

    /**
     * Invalid overtime hours
     */
    public static function invalidHours(): self
    {
        return new self('عدد الساعات غير صحيح');
    }
}
