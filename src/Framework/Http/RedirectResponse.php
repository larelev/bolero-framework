<?php

namespace Bolero\Framework\Http;

class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    public function send(): void
    {
        header($this->buildHeader('location'), true, $this->getStatus());
        exit();
    }
}
