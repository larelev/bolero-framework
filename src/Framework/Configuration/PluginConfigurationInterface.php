<?php

namespace Bolero\Framework\Configuration;

interface PluginConfigurationInterface extends ConfigurationInterface
{
    public static function viewsPaths(): array;
}
