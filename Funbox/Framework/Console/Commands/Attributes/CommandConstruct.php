<?php

namespace Funbox\Framework\Console\Commands\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class CommandConstruct
{
    public function __construct(
        public array $inject = [],
    ) {
    }
}
