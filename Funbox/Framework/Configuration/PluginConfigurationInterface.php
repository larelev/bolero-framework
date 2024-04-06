<?php

namespace Funbox\Framework\Configuration;

use Funbox\Framework\Configuration\ConfigurationInterface;

interface PluginConfigurationInterface extends ConfigurationInterface
{
    public static function viewsPaths(): array;
}
