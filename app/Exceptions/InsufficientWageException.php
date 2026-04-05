<?php

namespace App\Exceptions;

use Exception;

class InsufficientWageException extends Exception
{
    protected $message = 'Deduction amount exceeds the worker\'s daily wage.';
}
