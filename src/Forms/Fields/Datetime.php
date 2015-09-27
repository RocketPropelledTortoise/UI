<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Mangage dateTime fields
 */

/**
 * Creates a datefield with Form_element
 *
 * @author Stéphane Goetz
 */
class Datetime extends Field
{
    /**
     * Adds some specific attributes to be able to use the date picker automatically
     */
    protected function inputAttributes()
    {
        parent::inputAttributes();
        $this->input_attributes['type'] = 'text';
    }

    /**
     * Adds the script logic to the picker
     */
    protected function renderScript()
    {
        $options_date = json_encode_with_functions(
            [
                'firstDay' => 1,
                'format' => 'dd/mm/yyyy',
                'selectMonths' => true,
                'selectYears' => true,
                'onSet' => "function(item) {
                    if ( ! 'select' in item ) return;
                    setTimeout( function() { $('#{$this->id}').get(0).updateDate(); }, 0 );
                }",
            ]
        );

        $options_time = json_encode_with_functions(
            [
                'format' => 'H:i',
                'onSet' => "function(item) {
                    if ( ! 'select' in item ) return;
                    setTimeout( function() { $('#{$this->id}').get(0).updateDate(); }, 0 );
                }",
            ]
        );

        $this->getJS()->ready(
            "
            var element = $('#{$this->id}')
            element.data('date', $('#{$this->id}_date').pickadate($options_date).pickadate('picker'));
            element.data('time', $('#{$this->id}_time').pickatime($options_time).pickatime('picker'));

            element.get(0).updateDate = function() {
                var main = $(this);
                main.val(
                    main.data('date').get('select', 'dd/mm/yyyy') + ' ' + main.data('time').get('select', 'HH:i')
                );
            }

            "
        );
    }

    /**
     * Render the inner field
     */
    protected function renderInner()
    {
        $attr = $this->input_attributes;
        $date = explode(' ', $attr['value']) + [1 => ''];

        $field_main = array_merge($attr, ['type' => 'hidden']);
        $this->result .= '<input' . $this->renderAttributes($field_main) . ' />';

        $this->result .= '<div class=row><div class=col-xs-8>';

        $field_date = array_merge($attr, ['id' => $attr['id'] . '_date', 'name' => $attr['id'] . '_date']);
        $field_date['value'] = $date[0];
        $this->result .= '<input' . $this->renderAttributes($field_date) . ' />';

        $this->result .= '</div><div class=col-xs-4>';

        $field_time = array_merge($attr, ['id' => $attr['id'] . '_time', 'name' => $attr['id'] . '_time']);
        $field_time['value'] = $date[1];
        $this->result .= '<input' . $this->renderAttributes($field_time) . ' />';

        $this->result .= '</div></div>';
    }
}
