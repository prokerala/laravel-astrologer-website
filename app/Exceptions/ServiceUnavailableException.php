<?php

namespace App\Exceptions;

use Exception;

class ServiceUnavailableException extends Exception
{
    protected $userMessage;

    public function __construct(string $userMessage, string $logMessage = null, int $code = 0, Exception $previous = null)
    {
        $this->userMessage = $userMessage;
        parent::__construct($logMessage ?: $userMessage, $code, $previous);
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}