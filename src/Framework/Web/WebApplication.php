<?php

namespace Bolero\Framework\Web;

use Bolero\Framework\Core\AbstractApplication;
use Bolero\Framework\Http\Kernel;
use Bolero\Framework\Http\Request;

class WebApplication extends AbstractApplication
{
    public function __construct()
    {
        $this->run();
    }

    public function run(): void
    {
        $request = new Request();

        $container = require BASE_PATH . 'Bootstrap' . DIRECTORY_SEPARATOR . 'web.php';

        $container->add(\Bolero\Framework\MVC\AbstractController::class);

        $inflector = $container->inflector(\Bolero\Framework\MVC\AbstractController::class);
        $inflector->invokeMethod('setContainer', [$container]);
        $inflector->invokeMethod('setRequest', [$request]);

        $container->addShared(
            \Bolero\Framework\Http\HistoryInterface::class,
            \Bolero\Framework\Http\History::class,
        );

        $filename = BASE_PATH . 'Factories' . DIRECTORY_SEPARATOR . 'TwigFactory.php';
        if (!file_exists($filename)) {
            $container->add('template-renderer-factory', \Bolero\Framework\Template\TwigFactory::class);
        }

        $container->addShared('twig', function () use ($container) {
            return $container->get('template-renderer-factory')->create();
        });

        $kernel = $container->get(Kernel::class);
        $response = $kernel->handle($request);
        $response->send();

        $kernel->terminate($request, $response);
    }

    public static function create(): static
    {
        $clasName = WebApplication::class;
        return new $clasName;
    }
}
