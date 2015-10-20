<?php namespace Rocket\UI\Forms\ValidatorAdapters;


interface ValidatorInterface
{
    /**
     * Is this object supported by this validator ?
     *
     * @param object $object
     * @return bool
     */
    public static function supports($object);

    /**
     * Create a validator instance.
     *
     * @param object $validator
     * @param mixed $data
     * @param mixed $defaults
     */
    public function __construct($validator, $data, $defaults);

    /**
     * Get all errors for a field.
     *
     * @param $name
     * @return mixed
     */
    public function getErrors($name);

    /**
     * Has this field an error ?
     *
     * @param string $name
     * @return bool
     */
    public function hasError($name);

    /**
     * Get the value for a field.
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public function getValue($name, $default = "");

    /**
     * Is this field required ?
     *
     * @param string $name
     * @return bool
     */
    public function isRequired($name);
}
