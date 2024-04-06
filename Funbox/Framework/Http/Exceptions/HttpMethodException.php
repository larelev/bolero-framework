<?php

namespace Funbox\Framework\Http\Exceptions;

use Funbox\Framework\Http\HttpStatusCodeEnum;
use Throwable;

class HttpMethodException extends HttpException
{
    public function __construct(string $badMethod = "", ?array $allowedMethods = null, ?Throwable $previous = null)
    {
        $message = sprintf("HTTP method %s is not allowed.", $badMethod);
        if ($allowedMethods) {
            $message .= PHP_EOL . "Allowed methods are: " . implode($allowedMethods);
        }
        parent::__construct($message, HttpStatusCodeEnum::METHOD_NOT_ALLOWED, $previous);
    }
}
