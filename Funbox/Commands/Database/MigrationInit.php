<?php

namespace Funbox\Commands\Database;

use DateTimeImmutable;
use Funbox\Framework\Console\Commands\Attributes\Command;
use Funbox\Framework\Console\Commands\CommandInterface;

#[Command(name: "migration:init")]
#[Command(desc: "Creates a blank migration file in migrations directory.")]
class MigrationInit implements CommandInterface
{
    public function execute(array $params = []): int
    {
        try
        {
            $migrationNumber = (new DateTimeImmutable)->format('YmdHisu');
            $filename = MIGRATIONS_PATH . $migrationNumber . '.php';
            $script = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'MigrationClass.tpl');

            $script = str_replace('<Version />', $migrationNumber, $script);

            file_put_contents(BASE_PATH . $filename, $script);

            if (!file_exists(BASE_PATH . $filename)) {
                throw new \Exception(BASE_PATH . $filename . ' could not be written. Please, check the files permissions.');
            }
            echo 'New migration file created: ' . $filename . '.' . PHP_EOL;

        } catch (\Throwable $throwable) {
            throw $throwable;
        }

        return 0;
    }

}
