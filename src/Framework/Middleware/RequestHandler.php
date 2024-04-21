<?php

namespace Bolero\Framework\Middleware;

use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;
use Bolero\Plugins\FlashMessage\Middlewares\FlashMessenger;
use League\Container\DefinitionContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middleware = [
        ExtractRouteInfo::class,
        SessionManager::class,
        FlashMessenger::class,
        RouterDispatcher::class,
        History::class,
    ];

    public function __construct(
        private readonly DefinitionContainerInterface $container
    )
    {

        if (file_exists(MIDDLEWARES)) {
            $this->middleware = require MIDDLEWARES;
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            return new Response("No request to handle.", 500);
        }

        $middlewareClass = array_shift($this->middleware);
        $middleware = $this->container->get($middlewareClass);
        $response = $middleware->process($request, $this);

        return $response;
    }

    public function injectMiddleware(array $middlewares): void
    {
        array_splice($this->middleware, 0, 0, $middlewares);
    }
}
