<?php

/**
 * Creates a Form_Element object and returns it to work with
 *
 * @deprecated Should not be used like this, use a facade or the twig extension
 * @param string $id
 * @param string $title
 * @param string $type
 * @return Form_Element
 */
function FE($id, $title = '', $type = 'text')
{
    return \Rocket\UI\Forms\Forms::field($id, $title, $type);
}
