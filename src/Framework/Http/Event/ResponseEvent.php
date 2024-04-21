<?php

namespace Bolero\Framework\Http\Event;

use Bolero\Framework\Event\Event;
use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private readonly Request  $request,
        private readonly Response $response,
    )
    {

    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
