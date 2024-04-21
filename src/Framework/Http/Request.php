<?php

namespace Bolero\Framework\Http;

use Bolero\Framework\Session\SessionInterface;
use Bolero\Plugins\FlashMessage\FlashMessageInterface;
use Exception;

class Request implements RequestInfoInterface
{
    private RequestInfo $info;
    private ?FlashMessageInterface $flashMessage = null;
    private ?SessionInterface $session = null;
    private mixed $routeHandler;
    private array $routeHandlerArgs;

    public function __construct()
    {
        $this->info = new RequestInfo();
    }

    public function getSession(): ?SessionInterface
    {
        return $this->session;
    }

    /**
     * @throws Exception
     */
    public function setSession(?SessionInterface $session): void
    {
        if ($this->session !== null) {
            throw new Exception("Session already instantiated.");
        }
        $this->session = $session;
    }

    public function getFlashMessage(): ?FlashMessageInterface
    {
        return $this->flashMessage;
    }

    /**
     * @throws Exception
     */
    public function setFlashMessage(?FlashMessageInterface $flashMessage): void
    {
        if ($this->flashMessage !== null) {
            throw new Exception("FlashMessage already instantiated.");
        }
        $this->flashMessage = $flashMessage;
    }

    public function getRouteHandler(): mixed
    {
        return $this->routeHandler;
    }

    public function setRouteHandler(mixed $routeHandler): void
    {
        $this->routeHandler = $routeHandler;
    }

    public function getRouteHandlerArgs(): array
    {
        return $this->routeHandlerArgs;
    }

    public function setRouteHandlerArgs(array $routeHandlerArgs): void
    {
        $this->routeHandlerArgs = $routeHandlerArgs;
    }

    public function getCookies($name = ''): array|string
    {
        return $this->info->getCookies($name);
    }

    public function getFiles(): array
    {
        return $this->info->getFiles();
    }

    public function getServer(): array
    {
        return $this->info->getServer();
    }

    public function getPathInfo(): string
    {
        return $this->info->getPathInfo();
    }

    public function getMethod(): string
    {
        return $this->info->getMethod();
    }

    public function getInfo(): RequestInfo
    {
        return $this->info;
    }

    public function searchFromQuery(string $param): ?string
    {
        $value = $this->getGetParams($param);
        return !isset($value) ? null : $value;
    }

    public function getGetParams(string $param = ''): array|string
    {
        return $this->info->getGetParams($param);
    }

    public function searchFromBody(string $param): ?string
    {
        $value = $this->getPostParams($param);
        return !isset($value) ? null : $value;
    }

    public function getPostParams(string $param = ''): array|string
    {
        return $this->info->getPostParams($param);
    }
}
