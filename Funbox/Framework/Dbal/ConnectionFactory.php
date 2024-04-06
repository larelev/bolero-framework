<?php

namespace Funbox\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Driver\PDO\SQLite\Driver;

class ConnectionFactory
{
    public function __construct(private string $databaseURL)
    {
    }

    public function create(): Connection
    {
        return DriverManager::getConnection(['driverClass' => Driver::class, 'path' => $this->databaseURL]);
    }
}
