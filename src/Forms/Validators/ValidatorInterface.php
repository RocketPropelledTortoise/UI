<?php namespace Rocket\UI\Forms\Validators;


interface ValidatorInterface
{
    public function hasError($name);

    public function getRules($name);
}
