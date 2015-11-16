<?php namespace Rocket\UI\Forms\ValidatorAdapters;

use Illuminate\Validation\Validator;

class LaravelValidator implements ValidatorInterface
{
    /**
     * @var Validator
     */
    protected $validator;
    protected $data;
    protected $defaults;

    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * {@inheritdoc}
     */
    public function __construct($validator, $data, $defaults)
    {
        $this->validator = $validator;
        $this->data = $data;
        $this->defaults = $defaults;
    }

    /**
     * @return \Illuminate\Session\Store
     */
    protected function getSession()
    {
        //TODO :: do that by injection
        if (!$this->session) {
            $this->session = app('session');
        }

        return $this->session;
    }

    /**
     * {@inheritdoc}
     */
    public function hasError($name)
    {
        // The errors must be taken from the session, or else we have errors even if the form wasn't sent
        $session = $this->getSession();
        if ($session->has('errors')) {
            return $session->get('errors')->has($name);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors($name)
    {
        // The errors must be taken from the session, or else we have errors even if the form wasn't sent
        $session = $this->getSession();
        if ($session->has('errors')) {
            $errors = $session->get('errors');

            if ($errors->has($name)) {
                return $errors->get($name);
            }
        }

        return '';
    }

    /**
     * Transform key from array to dot syntax.
     *
     * @param  string $key
     *
     * @return string
     */
    protected function transformKey($key)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }

    /**
     * Get the current value.
     *
     * With the following priority:
     * 1. If the field was posted, take that value
     * 2. If there is a model that has a value, take it
     * 3. If there is a value defined when showing the field
     * 4. If there is a default set in the validator
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public function getValue($name, $default = "")
    {
        // 1.
        $old = $this->getSession()->getOldInput($this->transformKey($name));
        if (!is_null($old)) {
            return $old;
        }

        // 2.
        if (!empty($this->data) && $value = data_get($this->model, $this->transformKey($name))) {
            return $value;
        }

        // 3.
        if (!empty($default)) {
            return $default;
        }

        // 4.
        if (!empty($this->defaults) && $value = data_get($this->defaults, $this->transformKey($name))) {
            return $value;
        }

        return "";
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired($name)
    {
        $rules = $this->validator->getRules($name);
        if (array_key_exists($name, $rules)) {
            return is_array($rules[$name]) ? in_array('required', $rules[$name]) :
                str_contains($rules[$name], 'required');
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function supports($object)
    {
        return $object instanceof Validator;
    }
}
