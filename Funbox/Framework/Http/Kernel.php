<?php

namespace Funbox\Framework\Http;

use Funbox\Framework\Event\EventDispatcher;
use Funbox\Framework\Http\Event\ResponseEvent;
use Funbox\Framework\Http\Exceptions\HttpException;
use Funbox\Framework\Middleware\RequestHandlerInterface;
use League\Container\DefinitionContainerInterface;

class Kernel
{
    private string $appEnv;

    public function __construct(
        DefinitionContainerInterface $container,
        private readonly RequestHandlerInterface $requestHandler,
        private readonly EventDispatcher $dispatcher,
    ) {
        $this->appEnv = $container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {
        try {
            $response = $this->requestHandler->handle($request);
        } catch (\Exception $exception) {
            $response = $this->createExceptionResponse($exception);
        }

        $this->dispatcher->dispatch(new ResponseEvent($request, $response));

        return $response;
    }

    public function createExceptionResponse(\Exception $exception): Response
    {
        if (in_array($this->appEnv, ['dev', 'test'])) {
            throw $exception;
        }

        if ($exception instanceof HttpException) {
            $response = new Response(content: $exception->getMessage(), status: $exception->getCode());
        }

        return new Response(content: 'Server error!', status: HttpStatusCodeEnum::SERVER_ERROR);
    }

    public function terminate(Request $request, Response $response): void
    {
        $request->getFlashMessage()->clear();
    }
}
