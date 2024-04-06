<?php

namespace Funbox\Framework\Event;

interface StoppableEventInterface
{
    public function isPropagationStopped(): bool;

}
