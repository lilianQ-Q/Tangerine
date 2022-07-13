<?php

namespace Tangerine\exceptions;

class IncludeException extends TangerineBaseExceptions
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}