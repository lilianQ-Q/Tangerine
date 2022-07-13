<?php

namespace Tangerine\exceptions;

class TangerineBaseExceptions extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}

?>