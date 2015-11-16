<?php namespace Rocket\UI\Forms;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\Log;
use Rocket\UI\Forms\ValidatorAdapters\ValidatorInterface;

/**
 * Class Forms
 * @method static Fields\Date date($id, $title = "")
 * @method static Fields\Time time($id, $title = "")
 * @method static Fields\Datetime datetime($id, $title = "")
 * @method static Fields\Textarea textarea($id, $title = "")
 * @method static Fields\Htmlarea htmlarea($id, $title = "")
 * @method static Fields\Field text($id, $title = "")
 * @method static Fields\Password password($id, $title = "")
 * @method static Fields\Radio radio($id, $title = "")
 * @method static Fields\Email email($id, $title = "")
 * @method static Fields\Autocomplete autocomplete($id, $title = "")
 * @method static Fields\Select select($id, $title = "")
 * @method static Fields\Checkbox checkbox($id, $title = "")
 * @method static Fields\Hidden hidden($id, $title = "")
 * @method static Fields\Honeypot honeypot($id, $title = "")
 * @method static Fields\File file($id, $title = "")
 * @method static Fields\Kaptcha kaptcha($id, $title = "")
 */
class Forms
{
    /**
     * Hold the configuration array
     *
     * @var array
     */
    public static $config;

    public static $currentValidator;
    public static $currentData;

    public static $adapters = [
        \Rocket\UI\Forms\ValidatorAdapters\LaravelValidator::class,
        \Rocket\UI\Forms\ValidatorAdapters\CodeIgniterFormValidator::class,
    ];

    /**
     * Set the current configuration
     *
     * @param $config
     */
    public static function setConfig($config)
    {
        self::$config = $config;
    }

    /**
     * @param object $validator
     * @param mixed $data
     * @param mixed $defaults
     * @return bool
     */
    public static function setFormValidator($validator, $data = [], $defaults = [])
    {
        foreach (self::$adapters as $class) {
            if ($class::supports($validator)) {
                self::$currentValidator = new $class($validator, $data, $defaults);

                return true;
            }
        }

        throw new \RuntimeException('impossible to find a form adapter for ' . get_class($validator));
    }

    /**
     * @return ValidatorInterface
     */
    public static function getFormValidator()
    {
        if (!self::$currentValidator) {
            Log::debug('You have no form validator defined');
        }

        return self::$currentValidator;
    }

    /**
     * @param string $adapter
     */
    public static function addFormValidatorAdapter($adapter)
    {
        if (!is_subclass_of($adapter, ValidatorInterface::class)) {
            throw new \RuntimeException("$adapter doesn't implement ValidatorInterface");
        }

        self::$adapters[] = $adapter;
    }

    /**
     * Get the list of field types
     *
     * @return mixed
     */
    public static function getFieldTypes()
    {
        return self::$config['field_types'];
    }

    /**
     * Shortcut to create a field
     *
     * @param $type
     * @param $arguments
     * @return Fields\Field
     */
    public static function __callStatic($type, $arguments)
    {
        $types = self::getFieldTypes();

        if (!array_key_exists($type, $types)) {
            throw new \RuntimeException("Cannot create field of type $type");
        }

        return self::field($arguments[0], array_key_exists(1, $arguments) ? $arguments[1] : '', $type);
    }

    /**
     * Create a field
     *
     * @param string $id
     * @param string $title
     * @param string $type
     * @return \Rocket\UI\Forms\Fields\Field
     */
    public static function field($id, $title = '', $type = 'text')
    {
        $types = self::getFieldTypes();

        $data = ['title' => $title];

        //Generates the class if we find the right renderer
        if (!array_key_exists($type, $types)) {
            $type = 'text';
        }

        return new $types[$type]($id, $data);
    }

    public static function getValue($field, $default = '')
    {
        $validator = static::getFormValidator();

        if ($validator) {
            return $validator->getValue($field, $default);
        }
    }

    public static function open(array $options = [])
    {
        if (array_key_exists('validator', $options)) {
            static::setFormValidator($options['validator']);
            unset($options['validator']);
        }

        return Form::open($options);
    }

    public static function close()
    {
        static::$currentValidator = null;

        return Form::close();
    }

    public static function submit($value = null, $options = [])
    {
        return Form::submit($value, $options);
    }
}
