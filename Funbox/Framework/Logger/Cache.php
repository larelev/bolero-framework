<?php

namespace Funbox\Framework\Logger;

class Cache
{
    private const LOG_PATH = VAR_PATH . 'logs' . DIRECTORY_SEPARATOR;
    public const INFO_LOG = self::LOG_PATH . 'info.log';
    public const DEBUG_LOG = self::LOG_PATH . 'debug.log';
    public const ERROR_LOG = self::LOG_PATH . 'error.log';
    public const SQL_LOG = self::LOG_PATH . 'sql.log';
    public static function prepare(): void
    {
        $ok = file_exists(self::LOG_PATH);
        if (!$ok) {
            $ok = mkdir(self::LOG_PATH, 0775);
        }
        if (!$ok) {
            throw new \RuntimeException('Impossible to create directory' . self::LOG_PATH);
        }
    }

}
