<?php

namespace Bolero\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDO\SQLite\Driver;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function __construct(private readonly string $databaseURL)
    {
    }

    public function create(): Connection
    {
        return DriverManager::getConnection(['driverClass' => Driver::class, 'path' => $this->databaseURL]);
    }
}
