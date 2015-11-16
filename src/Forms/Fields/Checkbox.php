<?php
namespace Rocket\UI\Forms\Fields;

use Illuminate\Support\Collection;

/**
 * Adds a checkbox
 *
 * @method $this checked(boolean $checked)
 */
class Checkbox extends Field
{
    /**
     * Override the constructor for some options
     */
    public function __construct($id, $data = [])
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
        $this->input_attributes['class'] = ['checkbox']; //removes elm

        $this->input_attributes['value'] = $this->params['check_value'];

        if ($this->getCheckboxCheckedState()) {
            $this->input_attributes['checked'] = 'checked';
        }

        $this->params['label_position'] = 'after';
    }

    /**
     * Get the check state for a checkbox input.
     *
     * @return bool
     */
    protected function getCheckboxCheckedState()
    {
        $value = $this->input_attributes['value'];
        $default = array_key_exists('checked', $this->params) ? $this->params['checked'] : false;
        $current = $this->getValidator()->getValue($this->name, $default);

        if (is_array($current)) {
            return in_array($value, $current);
        } elseif ($current instanceof Collection) {
            return $current->contains('id', $value);
        } else {
            return (bool) $current;
        }
    }

    protected function classes()
    {
        parent::classes();

        $this->label_attributes['class'][] = 'checkbox';
    }
}
