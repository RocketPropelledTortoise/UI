<?php
namespace Rocket\UI\Forms\Fields;

    /**
     * Manage date fields
     */

/**
 * Creates a date field with Form_element
 *
 * @author Stéphane Goetz
 */
class Date extends Field
{
    /**
     * Adds some specific attributes to be able to use the date picker automatically
     */
    protected function inputAttributes()
    {
        parent::inputAttributes();

        $this->input_attributes['type'] = 'text';
        $this->input_attributes['class'][] = 'date';
    }

    /**
     * Adds the script logic to the picker
     */
    protected function renderScript()
    {
        $options = array(
            'formatSubmit' => "dd/mm/yyyy",
            'format' => "dd/mm/yyyy",
            'firstDay' => 1,
            'selectMonths' => true,
            'selectYears' => true
        );

        if (array_key_exists('options', $this->params)) {
            $options = array_merge($this->params['options'], $options);
        }

        $this->getJS()->ready('$( "#' . $this->id . '").pickadate(' . json_encode($options) . ');');
    }
}
