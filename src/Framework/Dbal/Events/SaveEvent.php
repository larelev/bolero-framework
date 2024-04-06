<?php

namespace Bolero\Framework\Dbal\Events;

use Bolero\Framework\Dbal\Entity;
use Bolero\Framework\Event\Event;

class SaveEvent extends Event
{
    public function __construct(private Entity $entity)
    {
    }
}
