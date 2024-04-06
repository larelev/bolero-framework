<?php

namespace Bolero\Framework\Registry;

use Bolero\Framework\Registry\AbstractRegistryInterface;
use Bolero\Framework\Registry\AbstractStaticRegistry;

class StateRegistry extends AbstractStaticRegistry
{
    private static $instance = null;

    public static function reset(): void
    {
        self::$instance = new StateRegistry;
        unlink(self::$instance->getCacheFilename());
    }

    public static function getInstance(): AbstractRegistryInterface
    {
        if (self::$instance === null) {
            self::$instance = new StateRegistry;
        }

        return self::$instance;
    }
}
