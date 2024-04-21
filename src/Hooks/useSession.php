<?php

namespace Bolero\Hooks;

use Bolero\Framework\Session\Session;

function useSession(): Session
{
    return new Session();
}
