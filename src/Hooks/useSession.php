<?php

namespace Bolero\Hooks;

function useSession()
{
    return new \Bolero\Framework\Session\Session();
}
