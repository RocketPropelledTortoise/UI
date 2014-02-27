<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 16.02.14
 * Time: 23:10
 */

namespace Rocket\UI\Forms\Validators;


class CodeIgniterFormValidator implements ValidatorInterface
{
    public function hasError($name)
    {
        return form_field_error($name);
    }

    public function getRules($name)
    {
        $OBJ = & _get_validation_object();

        //TODO :: add a list of available and unavaiable callbacks / validators ...

        if ($OBJ !== false) {
            return $OBJ->get_rules($name);
        }

        return;
    }
}
