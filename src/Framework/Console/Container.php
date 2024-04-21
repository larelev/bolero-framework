<?php

namespace Bolero\Framework\Console;

use Bolero\Framework\Console\Commands\CommandRunner;
use Bolero\Framework\Dbal\ConnectionFactory;
use Bolero\Framework\Event\EventDispatcher;
use Bolero\Framework\Logger\Logger;
use Bolero\Framework\Logger\LoggerInterface;
use Bolero\Framework\Middleware\ExtractRouteInfo;
use Bolero\Framework\Middleware\RequestHandler;
use Bolero\Framework\Middleware\RequestHandlerInterface;
use Bolero\Framework\Middleware\RouterDispatcher;
use Bolero\Framework\Routing\Router;
use Bolero\Framework\Routing\RouterInterface;
use Bolero\Framework\Routing\RoutesAggregator;
use Bolero\Framework\Session\Session;
use Bolero\Framework\Session\SessionInterface;
use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\DefinitionContainerInterface;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;

class Container
{
    public static function provide(): DefinitionContainerInterface
    {
        $container = new \League\Container\Container();
        $container->delegate(new ReflectionContainer(false));

        $routes = include RoutesAggregator::ROUTES_PATH;
        $dotenv = new Dotenv();

        $dotenv->load(BASE_PATH . '.env');

        $appEnv = $_SERVER['APP_ENV'];

        $container->add('APP_ENV', new StringArgument($appEnv));
        $container->add('base-commands-namespace',
            new StringArgument('Bolero\\Commands\\'),
        );
        $container->add('plugins-commands-namespace',
            new StringArgument('Bolero\\Plugins\\'),
        );
        $container->add('app-commands-namespace',
            new StringArgument('App\\Commands\\'),
        );

        $container->add(
            RouterInterface::class,
            Router::class,
        );

        $container->add(
            RequestHandlerInterface::class,
            RequestHandler::class
        )->addArgument($container);

        $container->add(\Bolero\Framework\Http\Kernel::class)
            ->addArguments([
                $container,
                RequestHandlerInterface::class,
                \Bolero\Framework\Event\EventDispatcher::class,
            ]);

        $container->addShared(EventDispatcher::class);

        $container->add(CommandRunner::class)
            ->addArgument($container);

        $container->add(\Bolero\Framework\Console\Kernel::class)
            ->addArguments([$container, CommandRunner::class]);

        $container->addShared(
            SessionInterface::class,
            Session::class,
        );

        $container->addShared(
            LoggerInterface::class,
            Logger::class,
        );

        $container->add(ConnectionFactory::class)
            ->addArgument(
                new StringArgument(DATABASE_URL)
            );

        $container->addShared(Connection::class, function () use ($container): Connection {
            return $container->get(ConnectionFactory::class)->create();
        });

        $container->add(RouterDispatcher::class)
            ->addArguments([
                RouterInterface::class,
                $container,
            ]);

        $container->add(ExtractRouteInfo::class)
            ->addArgument(new ArrayArgument($routes));

        return $container;

    }
}
