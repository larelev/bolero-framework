<?php

namespace Funbox\Framework\Core;

abstract class AbstractApplication
{
    abstract public static function create(): static;
}
