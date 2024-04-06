<?php

namespace Funbox\Framework\Logger;

interface LoggerInterface
{
    public function dump(string $message, object | array $object): void;

    public function debug(string | array | object $message, string $filename = '', int $line = -1): void;

    public function info(string $string, ...$params): void;

    public function sql(string | array | object $message, string $filename = '', int $line = -1): void;

    public function error(\Throwable $ex, string $filename = '', int $line = -1): void;

    public function getInfoLog(): string;

    public function getDebugLog(): string;

    public function getErrorLog(): string;

    public function getSqlLog(): string;

    public function clearAll(): void;

}
