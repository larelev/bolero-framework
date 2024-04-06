<?php

namespace Funbox\Framework\Middleware;

use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response;
}
