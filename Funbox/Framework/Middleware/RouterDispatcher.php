<?php

namespace Funbox\Framework\Middleware;

use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;
use Funbox\Framework\Routing\RouterInterface;
use League\Container\DefinitionContainerInterface;

readonly class RouterDispatcher implements MiddlewareInterface
{

    public function __construct(
        private RouterInterface $router,
        private DefinitionContainerInterface $container
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
        $response = call_user_func_array($routeHandler, $vars);

        return $response;
    }
}
