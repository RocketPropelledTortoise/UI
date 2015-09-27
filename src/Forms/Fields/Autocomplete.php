<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Manage autocomplete fields
 */

/**
 * Adds the autocomplete logic
 *
 * @author Stéphane Goetz
 */
class Autocomplete extends Field
{
    /**
     * {@inheritdoc}
     */
    protected function getDefaults()
    {
        return parent::getDefaults() + [
            'autocomplete' => [
                'multiple' => false,
                'length' => 2,
            ],
        ];
    }

    /**
     * Adds some attributes
     */
    protected function inputAttributes()
    {
        if (array_key_exists('placeholder', $this->params)) {
            $this->input_attributes['placeholder'] = $this->params['placeholder'];
        }

        $this->input_attributes['id'] = $this->id;
        $this->input_attributes['name'] = $this->name;
        $this->input_attributes['type'] = 'text';
    }

    /**
     * Renders the scripts
     */
    protected function renderScript()
    {
        if ($this->params['autocomplete']['multiple']) {
            $select_callback = '';
            if (isset($this->params['autocomplete']['callback'])) {
                $select_callback = $this->params['autocomplete']['callback'];
            }

            //TODO :: convert to jquery plugin
            $this->getJS()->ready(
                '$("#' . $this->id . '").autocomplete(
                    {
                        minLength:' . $this->params['autocomplete']['length'] . ',
                        source: function( request, response ) {
                    $.getJSON( "' . \URL::to($this->params['autocomplete']['url']) . '", {
                        term: request.term.split( /,\s*/ ).pop()
                    }, response );
                },
                search: function() {
                    // custom minLength
                    var term = this.value.split( /,\s*/ ).pop();
                    if (term.length < ' .
                $this->params['autocomplete']['length'] . ') {
                        return false;
                    }
                },
                            focus: function() {
                    // prevent value inserted on focus
                    return false;
                },
                            select: function( event, ui ) {
                    var terms = this.value.split( /,\s*/ );
                    // remove the current input
                    terms.pop();
                    terms.push( ui.item.value );
                    terms.push( "" );
                    this.value = terms.join( ", " );' .
                $select_callback . '

                    return false;
                }
                });'
            );
        } else {
            $params = [
                'minLength' => $this->params['autocomplete']['length'],
            ];

            if (!empty($this->params['autocomplete']['url'])) {
                $params['source'] = \URL::to($this->params['autocomplete']['url']);
            } elseif (!empty($this->params['autocomplete']['source'])) {
                $params['source'] = $this->params['autocomplete']['source'];
            }

            if (isset($this->params['autocomplete']['callback'])) {
                $params['select'] = 'function(event, ui) { ' . $this->params['autocomplete']['callback'] . ' }';
            }
            if (isset($this->params['autocomplete']['callback_search'])) {
                $params['search'] = 'function() { ' . $this->params['autocomplete']['callback_search'] . ' }';
            }

            $script = '$("#' . $this->id . '")' .
                '.autocomplete(' . json_encode_with_functions($params) . ');';
            $this->getJS()->ready($script);
        }
    }
}
