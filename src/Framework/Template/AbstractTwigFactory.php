<?php

namespace Bolero\Framework\Template;

use Bolero\Framework\Caching\Cache;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class AbstractTwigFactory
{

    public function create(): Environment
    {
        $templatesPaths = $this->getViewsPaths();
        $loader = new FilesystemLoader($templatesPaths);

        $twig = new Environment($loader, [
            'debug' => false,
            'cache' => Cache::CACHE_PATH,
        ]);
        $twig->addExtension(new DebugExtension());

        return $this->extendsTemplate($twig);
    }

    abstract public static function extendsTemplate(Environment $twig): Environment;

    public function getViewsPaths(): array
    {
        $viewsPaths = [APP_VIEWS_PATH];
        $additionalPaths = [];

        $filename = CONFIG_PATH . 'twig.php';
        if (file_exists($filename)) {
            $additionalPaths = include $filename;
        }

        return [
            ...$viewsPaths,
            ...$additionalPaths,
        ];
    }

}
