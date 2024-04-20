<?php

namespace Bolero\Framework\MVC;

use Bolero\Forms\Components\Component;
use Bolero\Forms\Registry\PluginRegistry;
use Bolero\Forms\Web\Application;
use Bolero\Framework\Http\Request;
use Bolero\Framework\Http\Response;
use Bolero\Forms\Core\Builder;
use Bolero\Forms\Registry\ComponentRegistry;
use Bolero\Forms\Registry\CacheRegistry;
use Bolero\Framework\Web\WebApplication;
use League\Container\DefinitionContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class AbstractController
{
    protected ?DefinitionContainerInterface $container = null;
    protected ?Request $request = null;

    /**
     * @throws \ErrorException
     */
    public function setContainer(DefinitionContainerInterface $container): void
    {
        if ($this->container !== null) {
            throw new \ErrorException('Container is already set!');
        }

        $this->container = $container;
    }

    /**
     * @throws \ErrorException
     */
    public function setRequest(Request $request): void
    {
        if ($this->request !== null) {
            throw new \ErrorException('Request is already set!');
        }
        $this->request = $request;

    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function renderTwig(string $template, array $parameters = [], int $status = 200, Response $response = null): Response
    {
        $content = $this->container->get('twig')->render($template, $parameters);

        $response ??= new Response($content, $status);

        return $response;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function render(string $template, array $parameters = [], int $status = 200, Response $response = null): Response
    {

        $app = Application::create();

        $content = $app->getHtml();

        $response ??= new Response($content, $status);

        return $response;
    }
}
