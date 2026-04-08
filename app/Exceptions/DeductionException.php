<?php

namespace App\Exceptions;

class DeductionException extends \RuntimeException
{
    /**
     * Exception for when worker was not distributed on the deduction date.
     */
    public static function workerNotDistributed(): self
    {
        return new self('العامل لم يتم توزيعه في هذا اليوم');
    }

    /**
     * Exception for attempting to reverse an already-reversed deduction.
     */
    public static function alreadyReversed(): self
    {
        return new self('تم إلغاء هذا الخصم بالفعل');
    }

    /**
     * Exception for when deduction is not found.
     */
    public static function notFound(): self
    {
        return new self('الخصم غير موجود');
    }
}
