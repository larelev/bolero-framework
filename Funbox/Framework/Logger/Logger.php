<?php

namespace Funbox\Framework\Logger;

use Funbox\Framework\Utils\Text;

class Logger implements LoggerInterface
{

    private static Logger|null $_logger = null;

    public function __construct()
    {
    }

    public static function create(): Logger
    {
        if (self::$_logger === null) {
            self::$_logger = new Logger();
        }
        return self::$_logger;
    }

    public function dump(string $message, mixed $object): void
    {
        $output = print_r($object, true);
        $isMultiLine = substr_count($output, PHP_EOL) > 1;
        $separator = $isMultiLine ? ':' . PHP_EOL : '::';
        $this->debug($message . $separator . print_r($object, true) . PHP_EOL);
    }

    public function debug(string | array | object $message, string $filename = '', int $line = -1): void
    {
        $this->_log(Cache::DEBUG_LOG, $message, $filename, $line);
    }

    private function _log(string $filepath, string | array | object $message, string $filename = '', int $line = -1): void
    {
        $message = (is_array($message) || is_object($message)) ? print_r($message, true) : $message;

        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755);
        }

        $handle = fopen($filepath, 'a');

        if (APP_PATH) {
            $filename = substr($filename, strlen(APP_PATH));
        }
        $message = date('Y-m-d h:i:s') . (isset($filename) ? ":$filename" : '') . ($line > -1 ? ":$line" : '') . " : $message" . PHP_EOL;
        fwrite($handle, $message . PHP_EOL);
        fclose($handle);
    }

    public function info(string $string, ...$params): void
    {
        $message = Text::format($string, $params);
        $this->_log(Cache::INFO_LOG, $message);
    }

    public function sql(string | array | object $message, string $filename = '', int $line = -1): void
    {
        $this->_log(Cache::SQL_LOG, $message, $filename, $line);
    }

    public function error(\Throwable $ex, string $filename = '', int $line = -1): void
    {
        $message = '';

        if ($ex instanceof \ErrorException) {
            $message .= 'Error severity: ' . $ex->getSeverity() . PHP_EOL;
        }
        $message .= 'Error code: ' . $ex->getCode() . PHP_EOL;
        $message .= 'In ' . $ex->getFile() . ', line ' . $ex->getLine() . PHP_EOL;
        $message .= 'With the message: ' . $ex->getMessage() . PHP_EOL;
        $message .= 'Stack trace: ' . $ex->getTraceAsString() . PHP_EOL;

        $this->_log(Cache::ERROR_LOG, $message, $filename, $line);
    }

    public function getInfoLog(): string
    {
        if (!\file_exists(Cache::INFO_LOG)) {
            return '';
        }
        return \file_get_contents(Cache::INFO_LOG);
    }

    public function getDebugLog(): string
    {
        if (!\file_exists(Cache::DEBUG_LOG)) {
            return '';
        }
        return \file_get_contents(Cache::DEBUG_LOG);
    }

    public function getErrorLog(): string
    {
        if (!\file_exists(Cache::ERROR_LOG)) {
            return '';
        }
        return \file_get_contents(Cache::ERROR_LOG);
    }

    public function getSqlLog(): string
    {
        if (!\file_exists(Cache::SQL_LOG)) {
            return '';
        }
        return \file_get_contents(Cache::SQL_LOG);
    }

    public function clearAll(): void
    {
        if (file_exists(Cache::INFO_LOG)) {
            unlink(Cache::INFO_LOG);
        }

        if (file_exists(Cache::DEBUG_LOG)) {
            unlink(Cache::DEBUG_LOG);
        }

        if (file_exists(Cache::ERROR_LOG)) {
            unlink(Cache::ERROR_LOG);
        }

        if (file_exists(Cache::SQL_LOG)) {
            unlink(Cache::SQL_LOG);
        }
    }
}
