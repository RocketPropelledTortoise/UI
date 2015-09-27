<?php namespace Rocket\UI\Forms\ValidatorAdapters;

use Illuminate\Validation\Validator;

class LaravelValidator implements ValidatorInterface {

    /**
     * @var Validator
     */
    protected $validator;

    protected $session;

    public function __construct($validator) {
        $this->validator = $validator;
    }

    /**
     * @return \Illuminate\Session\Store
     */
    protected function getSession()
    {
        if (!$this->session) {
            $this->session = app('session');
        }

        return $this->session;
    }

    public function hasError($name)
    {
        // The errors must be taken from the session, or else we have errors even if the form wasn't sent
        $session = $this->getSession();
        if ($session->has('errors')) {
            return $session->get('errors')->has($name);
        }

        return false;
    }

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

        return "";
    }

    public function getValue($name, $default = "")
    {
        if ($this->getSession()->hasOldInput($name)) {
            return $this->getSession()->getOldInput($name);
        }

        return $default;
    }

    public function isRequired($name)
    {
        $rules = $this->validator->getRules($name);
        if (array_key_exists($name, $rules)) {
            return is_array($rules[$name]) ? in_array('required', $rules[$name]) : str_contains($rules[$name], 'required');
        }

        return false;
    }

    public static function supports($object)
    {
        return $object instanceof Validator;
    }
}
