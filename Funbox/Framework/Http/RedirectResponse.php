<?php

namespace Funbox\Framework\Http;

use Doctrine\DBAL\Exception;

class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    public function send(): void
    {
        try {
            header($this->buildHeader('location'), true, $this->getStatus());
            exit();
        } catch (Exception $exception) {

        }
    }
}
