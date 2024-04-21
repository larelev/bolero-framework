<?php

namespace Bolero\Framework\Console\Exceptions;

use Bolero\Framework\Core\BaseException;
use Throwable;

class ConsoleException extends BaseException
{
    public function __construct(string $message, int $code = 0, null|Throwable $previous = null, ...$params)
    {
        parent::__construct(
            'An Console exception occurred with the message:%s %s',
            $code,
            $previous,
            PHP_EOL, $message
        );
    }
}
