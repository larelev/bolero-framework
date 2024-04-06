<?php

namespace Bolero\Framework\Console;

use Bolero\Framework\Console\Exceptions\ConsoleException;
use Bolero\Framework\Core\AbstractApplication;

class Application extends AbstractApplication
{

    public static function create(): static
    {
        $clasName = get_class();
        return new $clasName;
    }

    public function run(array $argv, int $argc): int
    {
        try {
            $container = require_once SERVICES_PATH;
            $kernel = $container->get(Kernel::class);

            return $kernel->handle($argv, $argc);
        } catch (ConsoleException $exception) {
            echo $exception->getMessage() . PHP_EOL;
            return 0;
        } catch (\Throwable $throwable) {
            echo 'FATAL ERROR: ' . $throwable->getMessage() . PHP_EOL;
            return 1;
        }
    }
}
