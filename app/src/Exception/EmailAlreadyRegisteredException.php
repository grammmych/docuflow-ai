<?php

namespace App\Exception;

final class EmailAlreadyRegisteredException extends \RuntimeException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('Email is already registered', 0, $previous);
    }
}