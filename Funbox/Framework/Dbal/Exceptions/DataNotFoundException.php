<?php

namespace Funbox\Framework\Dbal\Exceptions;

use Throwable;

class DataNotFoundException extends DataException
{
    public function __construct(string $message = "Data", ?Throwable $previous = null)
    {
        parent::__construct("%s not found!", 404, $previous, $message);
    }
}
