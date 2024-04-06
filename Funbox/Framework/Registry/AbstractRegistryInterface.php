<?php

namespace Funbox\Framework\Registry;

interface AbstractRegistryInterface
{
    function _write(string $key, mixed $value): void;

    function _read(string $key, mixed $value = null);

    function _push(string $key, mixed $value): void;

    function _items(): array;

    function _cache(bool $asArray = false): bool;

    function _uncache(bool $asArray = false): bool;

    function _exists(string $key): bool;

    function _delete(string $key): void;

    function _setCacheDirectory(string $directory): void;

    function _getCacheFilename(bool $asArray = false): string;

    function _getFlatFilename(): string;
}
