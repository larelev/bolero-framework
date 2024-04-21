<?php

namespace Bolero\Framework\Dbal;

use Bolero\Framework\Dbal\Events\SaveEvent;
use Bolero\Framework\Event\EventDispatcher;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

abstract class DataMapper
{
    public function __construct(
        protected Connection      $connection,
        protected EventDispatcher $dispatcher,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function save(Entity $entity): void
    {
        $this->insert($entity);
        $id = $this->connection->lastInsertId();
        $entity->setId($id);
        $this->dispatcher->dispatch(new SaveEvent($entity));
    }

    abstract public function insert(Entity &$entity): void;
}
