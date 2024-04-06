<?php

namespace Bolero\Framework\Middleware;

use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;
use Bolero\Framework\Middleware\MiddlewareInterface;

class Dummy implements MiddlewareInterface
{

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        return $requestHandler->handle($request);
    }
}
