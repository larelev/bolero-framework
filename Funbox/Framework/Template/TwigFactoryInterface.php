<?php

namespace Funbox\Framework\Template;

use Twig\Environment;

interface TwigFactoryInterface
{
    public static function extendsTemplate(Environment $twig): Environment;
}
