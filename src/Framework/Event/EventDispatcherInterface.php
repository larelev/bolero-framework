<?php

namespace Bolero\Framework\Event;

interface EventDispatcherInterface
{
    public function dispatch(StoppableEventInterface $event): StoppableEventInterface;

}
