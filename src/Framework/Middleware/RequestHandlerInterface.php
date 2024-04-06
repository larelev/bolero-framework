<?php

namespace Bolero\Framework\Middleware;

use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}
