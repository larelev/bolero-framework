<?php

namespace Funbox\Framework\Routing;

use Funbox\Framework\Http\Request;
use League\Container\DefinitionContainerInterface;

interface RouterInterface
{
    public function dispatch(Request $request, DefinitionContainerInterface $container): array;
}
