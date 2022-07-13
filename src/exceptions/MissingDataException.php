<?php

namespace Tangerine\exceptions;

class MissingDataException extends TangerineBaseExceptions
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}