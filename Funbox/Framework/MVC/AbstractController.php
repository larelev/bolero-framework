<?php

namespace Funbox\Framework\MVC;

use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;
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
    public function render(string $template, array $parameters = [], int $status = 200, Response $response = null): Response
    {
        $content = $this->container->get('twig')->render($template, $parameters);

        $response ??= new Response($content, $status);

        return $response;
    }
}
