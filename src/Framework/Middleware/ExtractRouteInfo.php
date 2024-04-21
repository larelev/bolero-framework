<?php

namespace Bolero\Framework\Middleware;

use Bolero\Framework\Http\Exceptions\HttpMethodException;
use Bolero\Framework\Http\Exceptions\HttpNotFoundException;
use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class ExtractRouteInfo implements MiddlewareInterface
{
    public function __construct(private readonly array $routes)
    {
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpMethodException
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {

        $dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
            foreach ($this->routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:

                $request->setRouteHandler($routeInfo[1]);
                $request->setRouteHandlerArgs($routeInfo[2]);

                if (is_array($routeInfo[1]) && isset($routeInfo[1][2])) {
                    $requestHandler->injectMiddleware($routeInfo[1][2]);
                }
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                throw new HttpMethodException($request->getMethod(), $routeInfo[1]);
            default:
                throw new HttpNotFoundException('Route not found');

        }
        return $requestHandler->handle($request);
    }
}
