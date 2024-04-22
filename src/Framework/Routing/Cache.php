<?php

namespace Bolero\Framework\Routing;

class Cache
{
    public static function prepare(): void
    {
        if (file_exists(RoutesAggregator::ROUTES_PATH)) {
            return;
        }
        require APP_PATH . 'Routes' . DIRECTORY_SEPARATOR . 'Web.php';
        RoutesAggregator::writeRuntimeFile();
    }
}
