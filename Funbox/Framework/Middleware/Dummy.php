<?php

namespace Funbox\Framework\Middleware;

use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;
use Funbox\Framework\Middleware\MiddlewareInterface;

class Dummy implements MiddlewareInterface
{

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        return $requestHandler->handle($request);
    }
}
