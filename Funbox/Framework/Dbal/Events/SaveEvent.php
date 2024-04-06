<?php

namespace Funbox\Framework\Dbal\Events;

use Funbox\Framework\Dbal\Entity;
use Funbox\Framework\Event\Event;

class SaveEvent extends Event
{
    public function __construct(private Entity $entity)
    {
    }
}
