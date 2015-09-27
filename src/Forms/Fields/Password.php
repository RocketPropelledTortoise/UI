<?php
namespace Rocket\UI\Forms\Fields;

/**
 * Manage password fields
 */

/**
 * Password text field
 *
 * @author Stéphane Goetz
 */
class Password extends Field
{
    /**
     * Extends the type
     * @param string $id
     * @param array $data
     */
    public function __construct($id, $data = [])
    {
        $this->type = 'password';

        parent::__construct($id, $data);
    }
}
