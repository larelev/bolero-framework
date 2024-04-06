<?php

namespace Funbox\Framework\Console\Exceptions;

use Funbox\Framework\Core\BaseException;
use Throwable;

class ConsoleException extends BaseException
{
    public function __construct(int $code = 0, null | Throwable $previous = null, string $message = "", ...$params)
    {
        parent::__construct(
            'An Console exception occurred with the message:%s %s',
            $code,
            $previous,
            PHP_EOL, $message
        );
    }
}
