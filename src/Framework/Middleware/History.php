<?php

namespace Bolero\Framework\Middleware;

use Bolero\Framework\Http\HistoryInterface;
use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;

class History implements MiddlewareInterface
{

    public function __construct(private readonly HistoryInterface $history)
    {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        $info = $request->getInfo();
        $this->history->set($info);

        return $requestHandler->handle($request);
    }
}
