<?php namespace Rocket\UI\Forms\ValidatorAdapters;

interface ValidatorInterface
{
    public static function supports($object);

    public function __construct($validator);

    public function getErrors($name);

    public function hasError($name);

    public function getValue($name, $default = '');

    public function isRequired($name);
}
