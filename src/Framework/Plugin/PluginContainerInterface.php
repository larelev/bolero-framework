<?php

namespace Bolero\Framework\Plugin;

use League\Container\DefinitionContainerInterface;

interface PluginContainerInterface
{
    public static function provide(DefinitionContainerInterface $container): DefinitionContainerInterface;

}
