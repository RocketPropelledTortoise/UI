<?php
namespace Rocket\UI\Forms\Fields;

    /**
     * Manage checkboxes
     */

/**
 * Adds a checkbox
 *
 * @author Stéphane Goetz
 */
class Checkbox extends Field
{
    /**
     * Override the constructor for some options
     */
    public function __construct($id, $data = array())
    {
        $this->type = 'checkbox';

        parent::__construct($id, $data);

        if (!array_key_exists('check_value', $this->params)) {
            $this->params['check_value'] = 'on';
        }
    }

    /**
     * Adds some attributes
     */
    protected function inputAttributes()
    {
        $this->input_attributes['id'] = $this->id;
        $this->input_attributes['name'] = $this->name;
        $this->input_attributes['type'] = 'checkbox';
        $this->input_attributes['class'] = array('checkbox'); //removes elm

        $this->input_attributes['value'] = $this->params['check_value'];

        if (set_checkbox($this->name, $this->params['check_value']) != '') {
            $this->input_attributes['checked'] = 'checked';
        }

        if (array_key_exists('checked', $this->params)
            && $this->params['checked'] !== false
        ) {
            $this->input_attributes['checked'] = 'checked';
        }

        $this->params['label_position'] = 'after';
    }

    protected function classes()
    {
        parent::classes();

        $this->label_attributes['class'][] = 'checkbox';
    }
}
