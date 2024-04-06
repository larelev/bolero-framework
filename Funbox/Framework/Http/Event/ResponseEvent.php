<?php

namespace Funbox\Framework\Http\Event;

use Funbox\Framework\Event\Event;
use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;

class ResponseEvent extends Event
{
    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function __construct(
        private Request $request,
        private Response $response,
    ) {

    }
}
