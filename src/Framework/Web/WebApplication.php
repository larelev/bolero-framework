<?php

namespace Bolero\Framework\Web;

use Bolero\Framework\Core\AbstractApplication;
use Bolero\Framework\Http\History;
use Bolero\Framework\Http\HistoryInterface;
use Bolero\Framework\Http\Kernel;
use Bolero\Framework\Http\Request;
use Bolero\Framework\MVC\AbstractController;
use Bolero\Framework\Template\TwigFactory;

class WebApplication extends AbstractApplication
{
    public function __construct()
    {
        $this->run();
    }

    public function run(): void
    {
        $request = new Request();

        $container = require BASE_PATH . 'bootstrap' . DIRECTORY_SEPARATOR . 'web.php';

        $container->add(AbstractController::class);

        $inflector = $container->inflector(AbstractController::class);
        $inflector->invokeMethod('setContainer', [$container]);
        $inflector->invokeMethod('setRequest', [$request]);

        $container->addShared(
            HistoryInterface::class,
            History::class,
        );

        $filename = BASE_PATH . 'Factories' . DIRECTORY_SEPARATOR . 'TwigFactory.php';
        if (!file_exists($filename)) {
            $container->add('template-renderer-factory', TwigFactory::class);
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
