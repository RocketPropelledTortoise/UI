<?php

/**
 * Javascript class
 */

namespace Rocket\UI\Script\Support\Laravel;

use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Contracts\JsonableInterface;

/**
 * Javascript class
 *
 * Store all javascript needed for the page to output it in a single block at the end of the page
 *
 * @package Provider
 */
class JS extends \Rocket\UI\Script\JS
{

    /**
     * Prepare the queue
     */
    public function __construct($queue)
    {
        parent::__construct($queue);
    }

    /**
     * Walk the settings array to output them as a pure array
     *
     * @return string
     */
    protected function resolveSettings()
    {
        $queue_copy = $this->queue['setting'];

        array_walk_recursive(
            $queue_copy,
            function (&$content) {

                if ($content instanceof ArrayableInterface) {
                    $content = $content->toArray();
                }

                if ($content instanceof JsonableInterface) {
                    $content = $content->toJson();
                }
            }
        );

        return $queue_copy;
    }

    public function __toString()
    {
        return "";
    }
}
