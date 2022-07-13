<?php

namespace Tangerine\exceptions;

class ExtendsException extends TangerineBaseExceptions
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}