<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 16.02.14
 * Time: 23:15
 */

namespace Rocket\UI\Forms\Validators;


interface ValidatorInterface
{
    public function hasError($name);

    public function getRules($name);
}
