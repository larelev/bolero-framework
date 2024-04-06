<?php

namespace Bolero\Framework\Http\Event;

interface ResponseEventListenerInterface
{
    public function __invoke(ResponseEvent $event);
}
