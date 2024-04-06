<?php

namespace Funbox\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Funbox\Framework\Dbal\Events\SaveEvent;
use Funbox\Framework\Event\EventDispatcher;

abstract class DataMapper
{
    public function __construct(
        protected Connection $connection,
        protected EventDispatcher $dispatcher,
    ) {
    }

    abstract public function insert(Entity &$entity): void;

    public function save(Entity $entity): void
    {
        $this->insert($entity);
        $id = $this->connection->lastInsertId();
        $entity->setId($id);
        $this->dispatcher->dispatch(new SaveEvent($entity));
    }
}
