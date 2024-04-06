<?php

namespace Bolero\Framework\Configuration;

use Bolero\Framework\Configuration\ConfigurationInterface;

interface PluginConfigurationInterface extends ConfigurationInterface
{
    public static function viewsPaths(): array;
}
