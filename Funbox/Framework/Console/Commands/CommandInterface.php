<?php

namespace Funbox\Framework\Console\Commands;

interface CommandInterface
{
    public function execute(array $params = []): int;
}
