<?php

namespace Funbox\Framework\Event;

interface EventListenerInterface
{
    public function __invoke(Event $event);
}
