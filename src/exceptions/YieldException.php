<?php

namespace Tangerine\exceptions;

class YieldException extends TangerineBaseExceptions
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}