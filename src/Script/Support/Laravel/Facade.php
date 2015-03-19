<?php namespace Rocket\UI\Script\Support\Laravel;

/**
 * Javascript facade
 */
class Facade extends \Illuminate\Support\Facades\Facade
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
