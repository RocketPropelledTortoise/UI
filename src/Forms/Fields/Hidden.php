<?php
namespace Rocket\UI\Forms\Fields;

    /**
     * Manage hidden fields
     */

/**
 * Hidden field
 *
 * @author Stéphane Goetz
 */
class Hidden extends Field
{
    /**
     * Extends the type
     * @param string $id
     * @param array $data
     */
    public function __construct($id, $data = array())
    {
        $this->type = 'hidden';
        $this->show_label = false;

        parent::__construct($id, $data);
    }

    /**
     * Override the attributes to stay hidden
     */
    protected function inputAttributes()
    {
        $this->input_attributes['name'] = $this->name;
        $this->input_attributes['id'] = $this->id;
        $this->input_attributes['type'] = 'hidden';
    }
}
