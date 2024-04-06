<?php

namespace Bolero\Framework\Event;

interface StoppableEventInterface
{
    public function isPropagationStopped(): bool;

}
