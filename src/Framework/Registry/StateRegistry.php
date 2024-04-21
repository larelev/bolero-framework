<?php

namespace Bolero\Framework\Registry;

class StateRegistry extends AbstractStaticRegistry
{
    private static ?AbstractRegistryInterface $instance = null;

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
