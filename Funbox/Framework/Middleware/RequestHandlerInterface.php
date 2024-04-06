<?php

namespace Funbox\Framework\Middleware;

use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}
