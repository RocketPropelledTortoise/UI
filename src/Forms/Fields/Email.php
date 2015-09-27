<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Manage email fields
 */

/**
 * Password text field
 *
 * @author Stéphane Goetz
 */
class Email extends Field
{
    /**
     * Overrides the type
     * @param string $id
     * @param array $data
     */
    public function __construct($id, $data = [])
    {
        $this->type = 'email';

        parent::__construct($id, $data);
    }
}
