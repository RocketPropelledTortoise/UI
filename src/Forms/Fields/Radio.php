<?php namespace Rocket\UI\Forms\Fields;

/**
 * Renders radio buttons
 *
 * @method $this checked(boolean $checked)
 * @method $this values(array $config)
 * @method $this check_value(string $value)
 */
class Radio extends Field
{
    /**
     * Extends the type
     * @param string $id
     * @param array $data
     */
    public function __construct($id, $data = [])
    {
        $this->type = 'radio';

        parent::__construct($id, $data);
    }

    /**
     * Adds input attributes
     */
    protected function inputAttributes()
    {
        $this->input_attributes['name'] = $this->name;
        $this->input_attributes['type'] = 'radio';
        $this->input_attributes['id'] = $this->id;
        $this->input_attributes['class'] = []; //removes elm

        if (array_key_exists('check_value', $this->params) && $this->params['check_value']) {
            $this->input_attributes['value'] = $this->params['check_value'];
        }

        if ($this->getValidator()->getValue($this->name) == $this->input_attributes['value']) {
            $this->input_attributes['checked'] = 'checked';
        } elseif (array_key_exists('checked', $this->params)) {
            if ($this->params['checked'] !== false) {
                $this->input_attributes['checked'] = 'checked';
            }
        }

        if (!array_key_exists('values', $this->params)) {
            $this->params['label_position'] = 'after';
        }

        if (array_key_exists('enabled', $this->params) && $this->params['enabled'] == false) {
            $this->input_attributes['disabled'] = 'disabled';
        }
    }

    /**
     * Renders the boxes
     */
    protected function renderInner()
    {
        if (array_key_exists('values', $this->params)) {
            unset($this->input_attributes['id']);

            $this->input_attributes['class'][] = 'form_multiple';
            foreach ($this->params['values'] as $key => $val) {
                $this->input_attributes['value'] = $key;
                if ($key == $this->params['value']) {
                    $this->input_attributes['checked'] = 'checked';
                } else {
                    unset($this->input_attributes['checked']);
                }
                $this->result .= '<label class=radio-inline>';
                $this->result .= '<input' . $this->renderAttributes($this->input_attributes) . ' />';
                $this->result .= '<span>' . $val . '</span>';
                $this->result .= '</label>';
            }
        } else {
            parent::renderInner();
        }
    }

    protected function classes()
    {
        parent::classes();

        if (!array_key_exists('values', $this->params)) {
            $this->label_attributes['class'][] = 'radio';
        }
    }
}
