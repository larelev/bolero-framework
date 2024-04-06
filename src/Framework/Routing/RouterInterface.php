<?php

namespace Bolero\Framework\Routing;

use Bolero\Framework\Http\Request;
use League\Container\DefinitionContainerInterface;

interface RouterInterface
{
    public function dispatch(Request $request, DefinitionContainerInterface $container): array;
}
