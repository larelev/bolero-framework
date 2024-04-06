<?php

namespace Funbox\Framework\Console\Commands\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class Command
{
    public function __construct(
        public string $name = '',
        public string $desc = '',
    ) {
    }
}
