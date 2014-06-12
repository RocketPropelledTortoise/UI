<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 03.02.14
 * Time: 21:16
 */

namespace Rocket\UI\Forms;

class Forms
{
    /**
     * Hold the configuration array
     *
     * @var array
     */
    public static $config;

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
     * Get the list of field types
     *
     * @return mixed
     */
    public static function getFieldTypes()
    {
        return self::$config['field_types'];
    }

    //TODO :: init field types statically
    //public static function __callStatic($method, $arguments)
    //{
    //}

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

        $data = array('title' => $title);

        //Generates the class if we find the right renderer
        if (!array_key_exists($type, $types)) {
            $type = 'text';
        }

        return new $types[$type]($id, $data);
    }
}
