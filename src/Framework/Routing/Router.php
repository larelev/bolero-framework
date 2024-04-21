<?php

namespace Bolero\Framework\Routing;

use Bolero\Framework\Http\Request;
use League\Container\DefinitionContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Router implements RouterInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function dispatch(Request $request, DefinitionContainerInterface $container): array
    {
        $routeHandler = $request->getRouteHandler();
        $routeHandlerArgs = $request->getRouteHandlerArgs();

        if (is_array($routeHandler)) {
            [$controllerId, $method] = $routeHandler;
            $controller = $container->get($controllerId);
            $routeHandler = [$controller, $method];
        }

        return [$routeHandler, $routeHandlerArgs];
    }
}
