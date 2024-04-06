<?php

namespace Funbox\Framework\Registry;

use Funbox\Framework\Registry\AbstractRegistryInterface;

interface StaticRegistryInterface
{
    public static function getInstance(): AbstractRegistryInterface;

    static function write(string $key, mixed $value): void;

    static function push(string $key, mixed $value): void;

    static function read(string $key, mixed $value = null);

    static function items(): array;

    static function cache(): bool;

    static function uncache(): bool;

    static function delete(string $key): void;

    static function exists(string $key): bool;

    static function setCacheDirectory(string $directory): void;

    static function getCacheFilename(): string;

    static function getFlatFilename(): string;
}
