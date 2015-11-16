<?php namespace Rocket\UI\Forms\Fields;

/**
 * Adds a select box
 *
 * @method $this values(array $config)
 */
class Select extends Field
{
    /**
     * Adds attributes to the field
     */
    protected function inputAttributes()
    {
        $this->input_attributes['id'] = $this->id;
        $this->input_attributes['name'] = $this->name;
        if (array_key_exists('enabled', $this->params)
            && $this->params['enabled'] == false
        ) {
            $this->input_attributes['disabled'] = 'disabled';
        }
    }

    /**
     * Renders the box
     */
    protected function renderInner()
    {
        $options = $this->params['values'];
        $selected = $this->params['value'];

        unset($this->input_attributes['value']);

        if (!is_array($selected)) {
            $selected = [$selected];
        }

        // If no selected state was submitted we will attempt to set it automatically
        if (count($selected) === 0) {
            // If the form name appears in the $_POST array we have a winner!
            if (\Request::has($this->name)) {
                $selected = [\Request::get($this->name)];
            }
        }

        if (count($selected) > 1) {
            $this->input_attributes['multiple'] = 'multiple';
        }

        $form = '<select ' . $this->renderAttributes($this->input_attributes) . '>';

        foreach ($options as $key => $val) {
            $key = (string) $key;

            if (is_array($val) && !empty($val)) {
                $form .= '<optgroup label="' . $key . '">';

                foreach ($val as $optgroup_key => $optgroup_val) {
                    $form .= $this->renderItem($optgroup_key, $optgroup_val, in_array($optgroup_key, $selected));
                }

                $form .= '</optgroup>';
            } else {
                $form .= $this->renderItem($key, $val, in_array($key, $selected));
            }
        }

        $form .= '</select>';

        $this->result .= $form;
    }

    /**
     * Renders an option
     *
     * @param $key string
     * @param $value string
     * @param $selected boolean
     * @return string
     */
    private function renderItem($key, $value, $selected)
    {
        $value = strval($value);

        return "<option value='$key'" . ($selected ? ' selected="selected"' : '') . ">$value</option>";
    }
}
