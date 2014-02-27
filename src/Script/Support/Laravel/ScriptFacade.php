<?php

/**
 * Javascript facade
 */

namespace Rocket\UI\Script\Support\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * Javascript facade
 */
class ScriptFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'js';
    }
}
