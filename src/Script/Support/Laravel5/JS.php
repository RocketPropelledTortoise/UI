<?php namespace Rocket\UI\Script\Support\Laravel5;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Javascript class
 *
 * Store all javascript needed for the page to output it in a single block at the end of the page
 */
class JS extends \Rocket\UI\Script\JS
{
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

                if ($content instanceof Arrayable) {
                    $content = $content->toArray();
                }

                if ($content instanceof Jsonable) {
                    $content = $content->toJson();
                }
            }
        );

        return $queue_copy;
    }

    /**
     * This method is used by Twig/TwigBridge when adding scripts in templates
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
