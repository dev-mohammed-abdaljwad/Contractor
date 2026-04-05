<?php

namespace App\Exceptions;

use Exception;

class DuplicateDistributionException extends Exception
{
    protected $message = 'This worker is already assigned to a company on this date.';
}
