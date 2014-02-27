<?php
namespace Rocket\UI\Forms\Fields;

    /**
     * Manage textarea
     */

/**
 * Creates a Textarea with Form_element
 *
 * @author Stéphane Goetz
 */
class Textarea extends Field
{
    /**
     * Change the textarea attributes to be compatible
     */
    protected function inputAttributes()
    {
        $this->input_attributes['id'] = $this->id;
        $this->input_attributes['name'] = $this->name;
        $this->input_attributes['cols'] = 90;
        $this->input_attributes['rows'] = $this->params['height'];
    }

    /**
     * Render the file as a textarea and not input
     */
    protected function renderInner()
    {
        $val = $this->input_attributes['value'];
        unset($this->input_attributes['value']);

        $attributes = $this->renderAttributes($this->input_attributes);

        $this->result .= "<textarea $attributes>" . form_prep($val, $this->id) . "</textarea>";
    }
}
