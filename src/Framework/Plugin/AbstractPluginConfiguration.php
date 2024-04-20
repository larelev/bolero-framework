<?php

namespace Bolero\Framework\Plugin;

abstract class AbstractPluginConfiguration
{
    protected static function getViewsPath(string $dir): string
    {
        return $dir . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR;
    }

    protected static function getRoutes(string $dir): void
    {
        include $dir . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'Web.php';
    }

    protected static function getCommandsLocation(string $dir, string $namespace): array
    {
        return [
            'directory' => $dir . DIRECTORY_SEPARATOR . 'Commands',
            'namespace' => $namespace . '\\Commands\\',
        ];
    }

}