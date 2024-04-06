<?php

namespace Funbox\Framework\Event;

interface EventDispatcherInterface
{
    public function dispatch(StoppableEventInterface $event): StoppableEventInterface;

}
