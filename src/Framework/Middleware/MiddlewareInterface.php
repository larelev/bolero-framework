<?php

namespace Bolero\Framework\Middleware;

use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response;
}
