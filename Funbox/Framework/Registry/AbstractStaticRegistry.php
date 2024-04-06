<?php

namespace Funbox\Framework\Registry;

abstract class AbstractStaticRegistry extends AbstractRegistry implements StaticRegistryInterface, AbstractRegistryInterface
{
    abstract public static function reset(): void;

    public static function safeWrite(string $key, mixed $value): bool
    {
        if (false === $result = static::exists($key)) {
            static::write($key, $value);
        }

        return $result;
    }

    public static function exists(string $key): bool
    {
        return static::getInstance()->_exists($key);
    }

    abstract public static function getInstance(): AbstractRegistryInterface;

    public static function write(string $key, mixed $value): void
    {
        static::getInstance()->_write($key, $value);
    }

    public static function push(string $key, mixed $value): void
    {
        static::getInstance()->_push($key, $value);
    }

    public static function read(string $key, mixed $value = null)
    {
        return static::getInstance()->_read($key, $value);
    }

    public static function items(): array
    {
        return static::getInstance()->_items();
    }

    public static function cache(bool $asArray = false): bool
    {
        return static::getInstance()->_cache($asArray);
    }

    public static function uncache(bool $asArray = false): bool
    {
        return static::getInstance()->_uncache($asArray);
    }

    public static function delete(string $key): void
    {
        static::getInstance()->_delete($key);
    }

    public static function setCacheDirectory(string $directory): void
    {
        static::getInstance()->_setCacheDirectory($directory);
    }

    public static function getCacheFilename(bool $asArray = false): string
    {
        return static::getInstance()->_getCacheFilename($asArray);
    }

    public static function getFlatFilename(): string
    {
        return static::getInstance()->_getFlatFilename();
    }

}
