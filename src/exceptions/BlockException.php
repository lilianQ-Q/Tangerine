<?php

namespace Tangerine\exceptions;

class BlockException extends TangerineBaseExceptions
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}