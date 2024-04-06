<?php

namespace Funbox\Framework\Middleware;

use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;
use Funbox\Plugins\FlashMessage\Middlewares\FlashMessenger;
use League\Container\DefinitionContainerInterface;

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
    ) {

        if (file_exists(MIDDLEWARES)) {
            $this->middleware = require MIDDLEWARES;
        }
    }

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
