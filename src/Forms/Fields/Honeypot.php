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
class Honeypot extends Field
{
    /**
     * Extends the type
     * @param string $id
     * @param array $data
     */
    public function __construct($id, $data = [])
    {
        $this->id = $data;
        $this->time_field = $data['time'];
    }

    /**
     * Override the attributes to stay hidden
     */
    public function render()
    {
        return (new \Msurguy\Honeypot\Honeypot())->getFormHTML($this->id, $this->time_field);
    }
}
