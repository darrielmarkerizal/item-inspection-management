<?php

namespace Modules\Core\Exceptions;

use Exception;

class InvalidStatusTransitionException extends Exception
{
    public function __construct(string $from, string $to)
    {
        parent::__construct(
            "Cannot transition inspection status from '{$from}' to '{$to}'."
        );
    }
}