<?php

namespace Funbox\Framework\Middleware;

use Funbox\Framework\Http\HistoryInterface;
use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;

class History implements MiddlewareInterface
{

    public function __construct(private HistoryInterface $history)
    {
    }

    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
        $info = $request->getInfo();
        $this->history->set($info);

        return $requestHandler->handle($request);
    }
}
