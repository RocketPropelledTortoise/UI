<?php
namespace Rocket\UI\Forms\Support\Twig;

use Twig_Extension;

/**
 * Form extension for twig
 */

/**
 * Form extension for twig
 *
 * @author Stéphane Goetz
 */
class Extension extends Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(
            new TokenParser(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form';
    }
}
