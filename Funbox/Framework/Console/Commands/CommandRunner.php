<?php

namespace Funbox\Framework\Console\Commands;

use Funbox\Framework\Console\Exceptions\ConsoleException;
use League\Container\DefinitionContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CommandRunner
{

    public function __construct(private readonly DefinitionContainerInterface $container)
    {
    }

    /**
     * @throws ConsoleException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws \Exception
     */
    public function run(array $argv, int $argc): int
    {
        $commandName = $argv[1] ?? false;

        if (!$commandName) {
            throw new ConsoleException('Missing command name!');
        }

        $command = $this->container->get($commandName);
        [$shortOptions, $longOptions] = $this->container->get($commandName . ':registered-params');

        $args = array_slice($argv, 2);

        $options = $this->parseOptions($args, $argc - 2, $shortOptions, $longOptions);
        $status = $command->execute($options);

        return $status;

    }

    /**
     * @throws \Exception
     */
    private function parseOptions(array $args, int $count, array $shortOptions, array $longOptions): array
    {
        $result = [];

        for ($i = 0; $i < $count; $i++) {
            $current = $args[$i];
            if (str_starts_with($current, '--') && str_contains($current, '=')) {
                [$key, $value] = explode('=', substr($current, 2));

                if (!in_array($key, $longOptions)) {
                    throw new \Exception('Unknown parameter ' . $key . '!');
                }
                $result[$key] = $value;
            } else
            if (str_starts_with($current, '--') && !str_contains($current, '=')) {
                $key = substr($current, 2);

                if (!in_array($key, $longOptions)) {
                    throw new \Exception('Unknown parameter ' . $key . '!');
                }
                $result[$key] = null;
            } else
            if (str_starts_with($current, '-')) {
                $next = $i + 1 < $count ? $args[$i + 1] : '';

                $key = substr($current, 1);

                if (!in_array($key, $shortOptions)) {
                    throw new \Exception('Unknown parameter ' . $key . '!');
                }
                $value = str_starts_with($next, '-') ? null : ($next == '' ? null : $next);

                $result[$key] = $value;
            }
        }

        return $result;
    }
}
