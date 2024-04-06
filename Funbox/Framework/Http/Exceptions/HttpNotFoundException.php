<?php

namespace Funbox\Framework\Http\Exceptions;

use Funbox\Framework\Http\HttpStatusCodeEnum;
use Throwable;

class HttpNotFoundException extends HttpException
{
    public function __construct(string $message = '', null | Throwable $previous = null, ...$params)
    {
        parent::__construct($message, HttpStatusCodeEnum::NOT_FOUND, $previous, $params);
    }
}
