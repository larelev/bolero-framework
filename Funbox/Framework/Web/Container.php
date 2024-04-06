<?php

namespace Funbox\Framework\Web;

use Funbox\Framework\Event\EventDispatcher;
use League\Container\DefinitionContainerInterface;

class Container
{
    public static function provide(): DefinitionContainerInterface
    {
        $container = new \League\Container\Container();
        $container->delegate(new \League\Container\ReflectionContainer(false));

        $routes = include \Funbox\Framework\Routing\RoutesAggregator::ROUTES_PATH;
        $dotenv = new \Symfony\Component\Dotenv\Dotenv();

        $dotenv->load(BASE_PATH . '.env');

        $appEnv = $_SERVER['APP_ENV'];

        $container->add('APP_ENV', new \League\Container\Argument\Literal\StringArgument($appEnv));
        $container->add('base-commands-namespace',
            new \League\Container\Argument\Literal\StringArgument('Funbox\\Commands\\'),
        );
        $container->add('plugins-commands-namespace',
            new \League\Container\Argument\Literal\StringArgument('Funbox\\Plugins\\'),
        );
        $container->add('app-commands-namespace',
            new \League\Container\Argument\Literal\StringArgument('App\\Commands\\'),
        );

        $container->add(
            \Funbox\Framework\Routing\RouterInterface::class,
            \Funbox\Framework\Routing\Router::class,
        );

        $container->add(
            \Funbox\Framework\Middleware\RequestHandlerInterface::class,
            \Funbox\Framework\Middleware\RequestHandler::class
        )->addArgument($container);

        $container->add(\Funbox\Framework\Http\Kernel::class)
            ->addArguments([
                $container,
                \Funbox\Framework\Middleware\RequestHandlerInterface::class,
                \Funbox\Framework\Event\EventDispatcher::class,
            ]);

        $container->addShared(EventDispatcher::class);

        $container->add(\Funbox\Framework\Console\Commands\CommandRunner::class)
            ->addArgument($container);

        $container->add(\Funbox\Framework\Console\Kernel::class)
            ->addArguments([$container, \Funbox\Framework\Console\Commands\CommandRunner::class]);

        $container->addShared(
            \Funbox\Framework\Session\SessionInterface::class,
            \Funbox\Framework\Session\Session::class,
        );

        $container->addShared(
            \Funbox\Framework\Logger\LoggerInterface::class,
            \Funbox\Framework\Logger\Logger::class,
        );

        $container->add(\Funbox\Framework\Dbal\ConnectionFactory::class)
            ->addArgument(
                new \League\Container\Argument\Literal\StringArgument(DATABASE_URL)
            );

        $container->addShared(\Doctrine\DBAL\Connection::class, function () use ($container): \Doctrine\DBAL\Connection {
            return $container->get(\Funbox\Framework\Dbal\ConnectionFactory::class)->create();
        });

        $container->add(\Funbox\Framework\Middleware\RouterDispatcher::class)
            ->addArguments([
                \Funbox\Framework\Routing\RouterInterface::class,
                $container,
            ]);

        $container->add(\Funbox\Framework\Middleware\ExtractRouteInfo::class)
            ->addArgument(new \League\Container\Argument\Literal\ArrayArgument($routes));

        return $container;

    }
}
