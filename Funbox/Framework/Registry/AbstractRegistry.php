<?php

namespace Funbox\Framework\Registry;

use Funbox\Framework\Caching\Cache;
use Funbox\Framework\Utils\File;
use Funbox\Framework\Utils\Text;

abstract class AbstractRegistry implements AbstractRegistryInterface
{
    private array $entries = [];
    private bool $isLoaded = false;
    private string $baseDirectory = Cache::CACHE_PATH;
    private string $cacheFilename = '';
    private string $flatFilename = '';

    public function _write(string $key, mixed $value): void
    {
        $this->entries[$key] = $value;
    }

    public function _push(string $key, mixed $value): void
    {
        if (!isset($this->entries[$key])) {
            $this->entries[$key] = [];
        }
        $this->entries[$key][] = $value;
    }

    public function _read(string $key, mixed $value = null)
    {
        if (!isset($this->entries[$key])) {
            return null;
        }

        if ($value === null) {
            return $this->entries[$key];
        }

        if (!isset($this->entries[$key][$value])) {
            return null;
        }

        return $this->entries[$key][$value];
    }

    public function _delete(string $key): void
    {
        unset($this->entries[$key]);
    }

    public function _exists(string $key): bool
    {
        return isset($this->entries[$key]);
    }

    public function _cache(bool $asArray = false): bool
    {
        $result = '';

        $entries = $this->_items();

        $result = json_encode($entries, JSON_PRETTY_PRINT);

        if ($asArray) {
            $result = Text::jsonToPhpReturnedArray($result);
            $funbox_root = FUNBOX_ROOT;
            if (DIRECTORY_SEPARATOR === '\\') {
                $funbox_root = str_replace('\\', '\\\\', FUNBOX_ROOT);
            }

            $result = str_replace('"' . $funbox_root, 'FUNBOX_ROOT . "', $result);
            $result = str_replace('"' . APP_PATH, 'APP_PATH . "', $result);
        }

        $registryFilename = $this->_getCacheFileName($asArray);
        $len = File::safeWrite($registryFilename, $result);

        return $len !== null;
    }

    public function _items(): array
    {
        return $this->entries;
    }

    public function _getCacheFileName(bool $asArray = false): string
    {
        if ($this->cacheFilename === '') {
            $this->cacheFilename = $this->baseDirectory . $this->_getFlatFilename($asArray);
        }

        return $this->cacheFilename . ($asArray ? '.php' : '.json');
    }

    public function _getFlatFilename(): string
    {
        return $this->flatFilename ?: $this->flatFilename = strtolower(str_replace('\\', '_', get_class($this)));
    }

    public function _uncache(bool $asArray = false): bool
    {
        $this->isLoaded = false;

        $registryFilename = $this->_getCacheFileName($asArray);
        $text = File::safeRead($registryFilename);
        $this->isLoaded = $text !== null;

        if ($this->isLoaded && !$asArray) {
            $this->entries = json_decode($text, JSON_OBJECT_AS_ARRAY);
        }

        if ($this->isLoaded && $asArray) {

            $fn = function () use ($registryFilename) {
                return include $registryFilename;
            };

            $dictionary = $fn();

            $this->entries = [];
            foreach ($dictionary as $key => $value) {
                $this->entries[$key] = $value;
            }
        }

        return $this->isLoaded;
    }

    public function _setCacheDirectory(string $directory): void
    {
        $directory = substr($directory, -1) !== DIRECTORY_SEPARATOR ? $directory . DIRECTORY_SEPARATOR : $directory;
        $this->baseDirectory = $directory;
    }

    private function _shortClassName(): string
    {
        $fqname = get_class($this);
        $nameParts = explode('\\', $fqname);
        $basename = array_pop($nameParts);

        return $basename;
    }

}
