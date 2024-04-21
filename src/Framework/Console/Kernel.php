<?php

namespace Bolero\Framework\Console;

use Bolero\Framework\Console\Commands\CommandInterface;
use Bolero\Framework\Console\Commands\CommandRunner;
use Bolero\Framework\Console\Exceptions\ConsoleException;
use Bolero\Framework\Registry\StateRegistry;
use League\Container\DefinitionContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;

readonly class Kernel
{
    public function __construct(
        private DefinitionContainerInterface $container,
        private CommandRunner                $commandRunner
    )
    {
    }

    /**
     * @param array $argv
     * @param int $argc
     * @return int
     * @throws ConsoleException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(array $argv, int $argc): int
    {
        $this->registerCommands();

        $status = $this->commandRunner->run($argv, $argc);

        return $status;
    }

    private function registerCommands(): void
    {
        $commandsLocations = [
            [
                'directory' => LIB_PATH . "Commands",
                'namespace' => 'Bolero\\Commands\\',
            ],
            [
                'directory' => APP_PATH . "Commands",
                'namespace' => 'App\\Commands\\',
            ],
        ];
        $additionalLocations = [];

        $filename = CONFIG_PATH . 'commands.php';
        if (file_exists($filename)) {
            $additionalLocations = include $filename;
        }

        $commandsLocations = [
            ...$commandsLocations,
            ...$additionalLocations,
        ];

        $i = 0;
        foreach ($commandsLocations as $location) {
            $current = (object)$location;
            $iterator = new RecursiveDirectoryIterator($current->directory);
            $commandFiles = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($commandFiles as $commandFile) {
                if (!$commandFile->isFile() || $commandFile->getExtension() !== 'php') {
                    continue;
                }

                $this->registerOneCommand($commandFile, $current->namespace, $current->directory, $i > 1);
            }
            $i++;
        }

    }

    /**
     * @throws \ReflectionException
     */
    private function registerOneCommand(SplFileInfo $commandFile, string $namespace, string $directory, bool $isPluginCommand): void
    {

        $l = strlen($directory);

        $baseDomain = $commandFile->getPath() !== '' ? substr($commandFile->getPath(), $l + 1) : '';
        $domain = $baseDomain !== '' ? $baseDomain . '\\' : '';
        $category = $baseDomain !== '' ? strtolower($baseDomain) . ':' : '';

        if (str_contains($category, DIRECTORY_SEPARATOR . 'commands')) {
            $category = str_replace(DIRECTORY_SEPARATOR . 'commands', '', $category);
        }

        if ($isPluginCommand) {
            $nsParts = explode('\\', $namespace);
            array_pop($nsParts);
            array_pop($nsParts);
            $category = strtolower(array_pop($nsParts)) . ':';
        }

        $fqCommandClass = str_replace('/', '\\', $namespace . $domain . $commandFile->getBaseName('.' . $commandFile->getExtension()));

        if (!is_subclass_of($fqCommandClass, CommandInterface::class)) {
            return;
        }

        $class = new ReflectionClass($fqCommandClass);

        $attributesArgs = [];
        $attributes = $class->getAttributes();
        foreach ($attributes as $attribute) {
            $attributesArgs = array_merge($attributesArgs, $attribute->getArguments());
        }

        $commandName = $category . $attributesArgs['name'];
        $containerArgs = $attributesArgs['inject'] ?? [];
        $shortParams = $attributesArgs['short'] ?? [];
        $longParams = $attributesArgs['long'] ?? [];
        $registeredParams = [$shortParams, $longParams];
        $help = $attributesArgs['desc'] ?? '';

        StateRegistry::push('commands:help', [$commandName => $help]);

        $this->container->addShared($commandName . ':registered-params', $registeredParams);

        $definition = $this->container->addShared($commandName, $fqCommandClass);
        if (count($containerArgs)) {
            $definition->addArguments($containerArgs);
        }

    }
}
