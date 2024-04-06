<?php

namespace Funbox\Commands\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Funbox\Framework\Console\Commands\Attributes\Command;
use Funbox\Framework\Console\Commands\Attributes\CommandArgs;
use Funbox\Framework\Console\Commands\Attributes\CommandConstruct;
use Funbox\Framework\Console\Commands\CommandInterface;
use Funbox\Framework\Console\Exceptions\ConsoleException;
use InvalidArgumentException;

#[Command(name: "migrations:migrate")]
#[Command(desc: "Adds, updates or removes tables from the database.")]
#[CommandArgs(short: ['u', 'd', 'r'])]
#[CommandArgs(long: ['up', 'down', 'remove'])]
#[CommandConstruct(inject: [Connection::class])]
class MigrationsMigrate implements CommandInterface
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function execute(array $params = []): int
    {
        try
        {
            $doUp = array_key_exists('u', $params) || array_key_exists('up', $params);
            $doDown = array_key_exists('d', $params) || array_key_exists('down', $params);
            $doRemove = array_key_exists('r', $params) || array_key_exists('remove', $params);
            $doError = array_key_exists('u', $params) && array_key_exists('up', $params);
            $doError = $doError || (array_key_exists('d', $params) && array_key_exists('down', $params));
            $doError = $doError || (array_key_exists('r', $params) && array_key_exists('remove', $params));
            $doError = $doError || ($doUp && $doDown && $doRemove);
            $doNothing = !$doUp && !$doDown && !$doRemove;

            if ($doError) {
                throw new InvalidArgumentException('Invalid arguments.');
            }

            $version = null;
            if ($doUp) {
                $version = !isset($params['u']) ? ($params['up'] ?? null) : $params['u'];
            } else if ($doDown) {
                $version = !isset($params['d']) ? ($params['down'] ?? null) : $params['d'];
            } else if ($doRemove) {
                $version = !isset($params['r']) ? ($params['remove'] ?? null) : $params['r'];
            }

            $this->connection->beginTransaction();
            $schemaMan = $this->connection->createSchemaManager();
            $schema = new Schema();

            if ($doNothing) {
                $this->createMigrationsTable($schemaMan, $schema);
                $this->connection->commit();

                return 0;
            }

            $appliedMigrations = $this->getAppliedMigrations();
            $migrationsFiles = $this->getMigrationsFiles();
            $migrationsToApply = array_diff($migrationsFiles, $appliedMigrations);

            if ($doUp) {
                $this->doUp($version, $migrationsToApply, $schemaMan);
            } else if ($doDown) {
                $this->doDown($version, $appliedMigrations, $schemaMan);
            } else if ($doRemove) {
                $this->doRemove($version, $appliedMigrations);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }

            $this->connection->commit();

            return 0;
        } catch (DbalException $exception) {
            $this->connection->rollBack();
            throw $exception;
        } catch (\Throwable $throwable) {
            throw $throwable;
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function doUp(?string $version, array $migrationsToApply, AbstractSchemaManager $schema): void
    {
        $isDirty = false;
        foreach ($migrationsToApply as $migrationFile) {
            [$migrationObject, $current] = $this->getMigrationObject($migrationFile, $schema);

            if ($version === null || $current == $version) {
                $isDirty = true;
                $migrationObject->up();
                $this->insertMigration($migrationFile);
            }
        }

        if (!$isDirty) {
            throw new ConsoleException('Nothing to do');
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function doDown(?string $version, array $appliedMigrations, AbstractSchemaManager $schema): void
    {
        $isDirty = false;
        foreach ($appliedMigrations as $migrationFile) {
            [$migrationObject, $current] = $this->getMigrationObject($migrationFile, $schema);

            if ($version === null || $current == $version) {
                $isDirty = true;
                $migrationObject->down();
                $this->deleteMigration($migrationFile);
            }
        }

        if (!$isDirty) {
            throw new ConsoleException('Nothing to do');
        }
    }

    /**
     * @throws ConsoleException
     * @throws DbalException
     */
    private function doRemove(?string $version, array $appliedMigrations): void
    {
        if ($version === null) {
            throw new InvalidArgumentException("The migration version is mandatory for this argument.");
        }

        $isDirty = false;
        foreach ($appliedMigrations as $migrationFile) {
            $current = pathinfo($migrationFile, PATHINFO_FILENAME);

            if ($version === null || $current == $version) {
                $isDirty = true;
                $this->deleteMigration($migrationFile);
                echo "The migration $migrationFile was removed from history table." . PHP_EOL;
            }
        }

        if (!$isDirty) {
            throw new ConsoleException('Nothing to do');
        }
    }

    private function getMigrationObject(string $migrationFile, AbstractSchemaManager $schema): array
    {
        if (!file_exists(BASE_PATH . MIGRATIONS_PATH . $migrationFile)) {
            throw new \ErrorException("Migration file $migrationFile not found!");
        }

        require BASE_PATH . MIGRATIONS_PATH . $migrationFile;

        $version = pathinfo($migrationFile, PATHINFO_FILENAME);
        $className = 'Migration_' . $version;

        return [new $className($schema), $version];
    }

    private function getMigrationsFiles(): array
    {
        $files = scandir(BASE_PATH . MIGRATIONS_PATH);

        $result = array_filter($files, function ($file) {
            $re = '/([0-9]{20})\.php/';
            preg_match($re, $file, $matches, PREG_OFFSET_CAPTURE, 0);
            return count($matches) > 0;
        });

        return $result;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function getAppliedMigrations(): array
    {
        $sql = "SELECT migration FROM migrations;";
        return $this->connection->executeQuery($sql)->fetchFirstColumn();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function createMigrationsTable(AbstractSchemaManager $schemaManager, Schema $schema): void
    {
        if ($schemaManager->tableExists('migrations')) {
            throw new ConsoleException('Nothing to do');
        }
        $this->connection->beginTransaction();

        $table = $schema->createTable('migrations');
        $table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
        $table->addColumn('migration', Types::STRING);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);

        $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

        $this->connection->executeQuery($sqlArray[0]);

        $this->connection->commit();

        echo 'Migrations table has been created.' . PHP_EOL;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function insertMigration(string $migration): void
    {
        $sql = "INSERT INTO migrations (migration) VALUES (?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $migration);

        $stmt->executeStatement();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function deleteMigration(string $migration): void
    {
        $sql = "DELETE FROM migrations WHERE migration = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $migration);

        $stmt->executeStatement();
    }
}
