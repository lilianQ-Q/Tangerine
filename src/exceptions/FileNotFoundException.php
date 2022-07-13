<?php

namespace Tangerine\exceptions;

class FileNotFoundException extends TangerineBaseExceptions
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}