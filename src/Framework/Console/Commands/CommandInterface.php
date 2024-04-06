<?php

namespace Bolero\Framework\Console\Commands;

interface CommandInterface
{
    public function execute(array $params = []): int;
}
