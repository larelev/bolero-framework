<?php

namespace Bolero\Framework\Event;

interface EventListenerInterface
{
    public function __invoke(Event $event);
}
