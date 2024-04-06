<?php

namespace Bolero\Framework\Console;

use Bolero\Framework\Event\EventDispatcher;
use League\Container\DefinitionContainerInterface;

class Container
{
    public static function provide(): DefinitionContainerInterface
    {
        $container = new \League\Container\Container();
        $container->delegate(new \League\Container\ReflectionContainer(false));

        $routes = include \Bolero\Framework\Routing\RoutesAggregator::ROUTES_PATH;
        $dotenv = new \Symfony\Component\Dotenv\Dotenv();

        $dotenv->load(BASE_PATH . '.env');

        $appEnv = $_SERVER['APP_ENV'];

        $container->add('APP_ENV', new \League\Container\Argument\Literal\StringArgument($appEnv));
        $container->add('base-commands-namespace',
            new \League\Container\Argument\Literal\StringArgument('Bolero\\Commands\\'),
        );
        $container->add('plugins-commands-namespace',
            new \League\Container\Argument\Literal\StringArgument('Bolero\\Plugins\\'),
        );
        $container->add('app-commands-namespace',
            new \League\Container\Argument\Literal\StringArgument('App\\Commands\\'),
        );

        $container->add(
            \Bolero\Framework\Routing\RouterInterface::class,
            \Bolero\Framework\Routing\Router::class,
        );

        $container->add(
            \Bolero\Framework\Middleware\RequestHandlerInterface::class,
            \Bolero\Framework\Middleware\RequestHandler::class
        )->addArgument($container);

        $container->add(\Bolero\Framework\Http\Kernel::class)
            ->addArguments([
                $container,
                \Bolero\Framework\Middleware\RequestHandlerInterface::class,
                \Bolero\Framework\Event\EventDispatcher::class,
            ]);

        $container->addShared(EventDispatcher::class);

        $container->add(\Bolero\Framework\Console\Commands\CommandRunner::class)
            ->addArgument($container);

        $container->add(\Bolero\Framework\Console\Kernel::class)
            ->addArguments([$container, \Bolero\Framework\Console\Commands\CommandRunner::class]);

        $container->addShared(
            \Bolero\Framework\Session\SessionInterface::class,
            \Bolero\Framework\Session\Session::class,
        );

        $container->addShared(
            \Bolero\Framework\Logger\LoggerInterface::class,
            \Bolero\Framework\Logger\Logger::class,
        );

        $container->add(\Bolero\Framework\Dbal\ConnectionFactory::class)
            ->addArgument(
                new \League\Container\Argument\Literal\StringArgument(DATABASE_URL)
            );

        $container->addShared(\Doctrine\DBAL\Connection::class, function () use ($container): \Doctrine\DBAL\Connection {
            return $container->get(\Bolero\Framework\Dbal\ConnectionFactory::class)->create();
        });

        $container->add(\Bolero\Framework\Middleware\RouterDispatcher::class)
            ->addArguments([
                \Bolero\Framework\Routing\RouterInterface::class,
                $container,
            ]);

        $container->add(\Bolero\Framework\Middleware\ExtractRouteInfo::class)
            ->addArgument(new \League\Container\Argument\Literal\ArrayArgument($routes));

        return $container;

    }
}
