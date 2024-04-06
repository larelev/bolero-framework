<?php

namespace Funbox\Framework\Caching;

class Cache
{
    public const CACHE_PATH = VAR_PATH . 'cache' . DIRECTORY_SEPARATOR;

    public static function prepare(): void
    {
        $ok = file_exists(self::CACHE_PATH);
        if (!$ok) {
            $ok = mkdir(self::CACHE_PATH, 0775);
        }
        if (!$ok) {
            throw new \RuntimeException('Impossible to create directory' . self::CACHE_PATH);
        }
    }
}
