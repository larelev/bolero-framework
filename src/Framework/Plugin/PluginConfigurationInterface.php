<?php

namespace Bolero\Framework\Plugin;

interface PluginConfigurationInterface
{
    public static function viewsPath(): string;

    public static function commandsLocation(): array;

    public static function getNamespace(): string;

    public static function routes(): void;

}