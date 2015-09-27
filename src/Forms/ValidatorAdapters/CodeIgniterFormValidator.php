<?php namespace Rocket\UI\Forms\ValidatorAdapters;

class CodeIgniterFormValidator implements ValidatorInterface
{
    /**
     * @var \Form_validation
     */
    protected $validator;

    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    public function getValue($name, $default = '')
    {
        return $this->validator->set_value($name, $default);
    }

    public function hasError($name)
    {
        return $this->validator->has_error($name);
    }

    public function getErrors($name)
    {
        return $this->validator->get_error($name);
    }

    protected function getRules($name)
    {
        return $this->validator->getRules($name);
    }

    public function isRequired($name)
    {
        if (!empty($this->validator->_field_required[$name])) {
            return $this->validator->_field_required[$name];
        } else {
            return false;
        }
    }

    public static function supports($object)
    {
        return $object instanceof \Form_validation;
    }
}
