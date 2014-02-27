<?php

namespace Rocket\UI\Forms\Support\Twig;

use Twig_Node;
use Twig_Compiler;

/**
 * Form extension for twig
 */

/**
 * Form extension for twig
 *
 * @author Stéphane Goetz
 */
class Node extends Twig_Node
{
    /**
     * Form parameters
     *
     * @var array
     */
    private $_parameters;

    /**
     * Form methods
     *
     * @var array
     */
    private $_methods;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $parameters = array(), array $methods = array(), $lineno = 0, $tag = null)
    {
        $this->_parameters = $parameters;
        $this->_methods = $methods;

        parent::__construct(array(new Twig_Node()), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler->write('echo \Rocket\UI\Forms\Forms::field(');

        $first = true;
        foreach ($this->_parameters as $param) {
            if ($first) {
                $first = false;
            } else {
                $compiler->raw(',');
            }

            $compiler->subcompile($param);
        }

        $compiler->raw(')');

        foreach ($this->_methods as $methods) {
            foreach ($methods as $method => $parameters) {
                $compiler->raw('->' . $method . '(');

                $first = true;

                foreach ($parameters as $param) {

                    if ($first) {
                        $first = false;
                    } else {
                        $compiler->raw(',');
                    }

                    $compiler->subcompile($param);
                }

                $compiler->raw(')');
            }
        }

        $compiler->raw('->render();');
    }
}
