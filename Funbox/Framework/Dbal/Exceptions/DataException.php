<?php

namespace Funbox\Framework\Dbal\Exceptions;

use Funbox\Framework\Core\BaseException;
use Throwable;

class DataException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, ...$params)
    {
        $baseMessage = sprintf($message, $params);
        parent::__construct(
            'A Data exception occurred with the message:%s %s',
            $code,
            $previous,
            PHP_EOL, $baseMessage
        );
    }
}
