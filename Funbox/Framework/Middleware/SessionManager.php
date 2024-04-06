<?php

namespace Funbox\Framework\Middleware;

use Funbox\Framework\Http\Request;
use Funbox\Framework\Http\Response;
use Funbox\Framework\Session\Session;
use Funbox\Framework\Session\SessionInterface;

readonly class SessionManager implements MiddlewareInterface
{

    public function __construct(
        private SessionInterface $session
    ) {
    }

    /**
     * @throws \Exception
     */
    public function process(Request $request, RequestHandlerInterface $requestHandler): Response
    {
//         $sessionId = $request->getCookies(session_name());
//         if(!empty($sessionId)) {
//             $this->session->start($sessionId);
//         }

//        $this->session->start();
        $request->setSession($this->session);

        return $requestHandler->handle($request);
    }
}
