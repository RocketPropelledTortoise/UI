<?php namespace Rocket\UI\Forms\ValidatorAdapters;

class CodeIgniterFormValidator implements ValidatorInterface
{
    /**
     * @var \Form_validation
     */
    protected $validator;

    /**
     * {@inheritdoc}
     */
    public function __construct($validator, $data, $defaults)
    {
        $this->validator = $validator;
        $validator->set_form_values($data);
        foreach ($defaults as $field => $value) {
            $validator->set_default($field, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($name, $default = '')
    {
        return $this->validator->set_value($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function hasError($name)
    {
        return $this->validator->has_error($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors($name)
    {
        return $this->validator->get_error($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function getRules($name)
    {
        return $this->validator->getRules($name);
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired($name)
    {
        if (!empty($this->validator->_field_required[$name])) {
            return $this->validator->_field_required[$name];
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function supports($object)
    {
        return $object instanceof \Form_validation;
    }
}
