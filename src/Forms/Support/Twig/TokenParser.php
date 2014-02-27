<?php

namespace Rocket\UI\Forms\Support\Twig;

use Twig_TokenParser;
use Twig_Token;
use Twig_Error_Syntax;
use Twig_NodeInterface;

/**
 * Form extension for twig
 */

/**
 * Form extension for twig
 *
 * @author Stéphane Goetz
 */
class TokenParser extends Twig_TokenParser
{

    /**
     * form valid methods
     *
     * @var array
     */
    private $valid_methods = array(
        'width',
        'height',

        'event',
        'checked',
        'value',
        'values',
        'first',
        'last',
        'id',
        'tip',
        'placeholder',
        'autocomplete',
        'inline',
        'data',
        'options',
        'class'
    );

    /**
     * Parses a token and returns a node.
     *
     * @param  Twig_Token                                   $token
     * @return Node|Twig_NodeInterface
     * @throws Twig_Error_Syntax
     */
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();

        $parameters_finished = false;

        $parameters = array();
        $methods = array();

        $method_name = '';
        $method_level = 0;
        $current_parameters = array();

        do {
            $next = true;

            $value_next = $this->parser->getStream()->look()->getValue();
            $value_current = $this->parser->getStream()->getCurrent()->getValue();

            //if we get a parenthesis, it means the next is a method
            if ($value_next == '(') {
                $method_level++;
                $valid_method = in_array($value_current, $this->valid_methods);
                if (!$parameters_finished && $valid_method) {
                    $parameters_finished = true;
                } elseif ($parameters_finished && $method_level == 1 && !$valid_method) {
                    $message = sprintf('The function "%s" does not exist for the Form module', $value_current);
                    throw new Twig_Error_Syntax($message);
                }
            }

            if (!$parameters_finished) {
                //Parameters

                $p = $this->parser->getExpressionParser()->parseExpression();
                $parameters[] = $p;
                $next = false;

            } else {
                //Methods

                if ($value_next == '(') {
                    $method_name = $value_current;
                } elseif (!in_array($value_current, array('(', ')', ','))) {
                    $current_parameters[] = $this->parser->getExpressionParser()->parseExpression();

                    $next = false;
                }

                if ($this->parser->getStream()->getCurrent()->getValue() == ')') {
                    $method_level--;
                    if ($method_name != '') {
                        $methods[][$method_name] = $current_parameters;
                        $current_parameters = array();
                        $method_name = '';
                        $next = true;
                    }
                }
            }

            if ($next) {
                $this->parser->getStream()->next();
            }
        } while (!$this->parser->getStream()->test(Twig_Token::BLOCK_END_TYPE));

        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        return new Node($parameters, $methods, $lineno, $this->getTag());
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'form';
    }
}
