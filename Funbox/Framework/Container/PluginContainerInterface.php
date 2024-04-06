<?php

namespace Funbox\Framework\Container;

use League\Container\DefinitionContainerInterface;

interface PluginContainerInterface
{
    public static function provide(DefinitionContainerInterface $container): DefinitionContainerInterface;

}
