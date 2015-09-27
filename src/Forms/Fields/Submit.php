<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Manage submit field
 */

/**
 * Submit field
 *
 * @author Stéphane Goetz
 * @deprecated
 */
class Submit extends Field
{
    /**
     * Extends the type
     * @param string $id
     * @param array $data
     */
    public function __construct($id, $data = [])
    {
        $this->type = 'submit';

        parent::__construct($id, $data);
    }

    /**
     * Remove the default class
     */
    protected function classes()
    {
        $this->label_attributes['class'] = ['columns'];
        $this->label_attributes['style']['float'] = 'left';
        $this->input_attributes['class'] = ['btn'];
        parent::classes();
    }

    /**
     * So that we can't set the width
     */
    protected function width()
    {
    }

    /**
     * Adds some specific attributes
     */
    protected function inputAttributes()
    {
        $this->input_attributes['name'] = $this->name;
        $this->input_attributes['type'] = 'submit';
        $this->input_attributes['value'] = $this->params['title'];
    }

    /**
     * Adds a title
     */
    protected function renderTitle()
    {
        $this->result .= '<span' . $this->renderAttributes($this->span_attributes) . '>&nbsp;</span>';

        if ($this->params['inline'] == false && $this->params['title'] != ''
            && $this->params['label_position'] == 'before') {
            $this->result .= '<br />';
        }
    }

    /**
     * Renders the field
     */
    protected function renderInner()
    {
        $this->result .= '<input' . $this->renderAttributes($this->input_attributes) . ' />';
    }
}
