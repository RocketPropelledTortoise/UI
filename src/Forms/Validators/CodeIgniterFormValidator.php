<?php namespace Rocket\UI\Forms\Validators;


class CodeIgniterFormValidator implements ValidatorInterface
{
    public function hasError($name)
    {
        return form_field_error($name);
    }

    public function getRules($name)
    {
        if (!method_exists('Controller', 'get_instance')) {
            return [];
        }

        $OBJ = & _get_validation_object();

        //TODO :: add a list of available and unavaiable callbacks / validators ...

        if ($OBJ !== false) {
            return $OBJ->get_rules($name);
        }

        return;
    }
}
