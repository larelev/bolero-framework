<?php

namespace Bolero\Framework\Console\Commands\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class CommandArgs
{
    public function __construct(
        public array $short = [],
        public array $long = []
    ) {
    }
}