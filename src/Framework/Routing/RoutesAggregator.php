<?php

namespace Bolero\Framework\Routing;

use Bolero\Framework\Caching\Cache;
use Bolero\Framework\Utils\Text;
use ReflectionException;
use ReflectionFunction;
use RuntimeException;
use SplFileObject;

class RoutesAggregator
{
    public const ROUTES_JSON_PATH = Cache::CACHE_PATH . 'routes.json';
    public const ROUTES_ARRAY_PATH = Cache::CACHE_PATH . 'routes.txt';
    public const ROUTES_PATH = Cache::CACHE_PATH . 'routes.php';

    public static function writeRuntimeFile(): void
    {
        $json = file_get_contents(self::ROUTES_JSON_PATH);

        $routes = Text::jsonToPhpReturnedArray($json);

        file_put_contents(self::ROUTES_PATH, $routes);
    }

    function aggregate(string $method, string $route, array|callable $controller, ?array $middlewares = null): void
    {
        $this->prepareCacheIfNotExists();

        $json = file_get_contents(self::ROUTES_JSON_PATH);

        $routes = json_decode($json, JSON_OBJECT_AS_ARRAY);

        $controllerString = $controller;
        if (is_callable($controller)) {
            $controllerString = $this->callableToString($controller);
        }

        if ($middlewares === null) {
            $routes[] = [$method, $route, $controllerString];
        } else {
            $routes[] = [$method, $route, $controllerString, $middlewares];
        }

        $json = json_encode($routes, JSON_PRETTY_PRINT);

        file_put_contents(self::ROUTES_JSON_PATH, $json);
    }

    private function prepareCacheIfNotExists(): void
    {
        if (file_exists(self::ROUTES_JSON_PATH)) {
            return;
        }

        if (!touch(self::ROUTES_JSON_PATH)) {
            throw new RuntimeException('Impossible to write ' . self::ROUTES_JSON_PATH . ' file.');
        }

        file_put_contents(self::ROUTES_JSON_PATH, '[]');
    }

    /**
     * @throws ReflectionException
     */
    private function callableToString(callable $controller): string
    {
        $ref = new ReflectionFunction($controller);

        $file = new SplFileObject($ref->getFileName());
        $file->seek($ref->getStartLine() - 1);

        $code = '';
        while ($file->key() < $ref->getEndLine()) {
            $code .= $file->current();
            $file->next();
        }

        $begin = strpos($code, 'function');
        $end = strrpos($code, '}');
        $code = substr($code, $begin, $end - $begin + 1);

        return $code;
    }

}
