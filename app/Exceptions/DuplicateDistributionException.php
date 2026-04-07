<?php

namespace App\Exceptions;

class DuplicateDistributionException extends \Exception
{
    public function __construct(private int $workerId)
    {
        parent::__construct("Worker ID {$workerId} is already assigned");
    }

    public function getWorkerId(): int
    {
        return $this->workerId;
    }
}
