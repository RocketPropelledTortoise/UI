<?php
namespace Rocket\UI\Forms\Support\Twig;

use Rocket\UI\Forms\Forms;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Form extension for twig
 *
 * @author Stéphane Goetz
 */
class Extension extends Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('forms_open', [Forms::class, 'open'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('forms_close', [Forms::class, 'close'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('forms_submit', [Forms::class, 'submit'], ['is_safe' => ['html']]),
        ];
    }

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
