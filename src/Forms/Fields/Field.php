<?php
namespace Rocket\UI\Forms\Fields;

    /**
     * Form field manager
     *
     * @author Stéphane Goetz
     */


/**
 * Creates a form field
 *
 * @author Stéphane Goetz
 *
 * @method Field value(string $value) the value in the field
 * @method Field inline(bool $enabled)
 * @method Field id(string $id)
 * @method Field autocomplete(array $config)
 */
class Field
{
    /**
     * The field name
     *
     * @var string
     */
    protected $name;

    /**
     * Options for the field
     *
     * @var array
     */
    protected $params;

    /**
     * Unique ID for the field
     * @var string
     */
    protected $id;

    /**
     * Show the label or not ?
     * @var boolean
     */
    protected $show_label = true;

    /**
     * The attributes that will be applied to the <input>
     * @var array
     */
    protected $input_attributes = array(
        'class' => array('elm', 'form-control')
    );

    /**
     * The attributes that will be applied to the <label>
     * @var array
     */
    protected $label_attributes = array(
        'class' => array('elm')
    );

    /**
     * The attributes that will be applied to the <span> with the label
     * @var array
     */
    protected $span_attributes = array(
        'class' => array('field-label', 'control-label')
    );

    /**
     * The final content of the field
     * @var string
     */
    protected $result;

    /**
     * Is the field required or not ?
     *
     * @var boolean
     */
    protected $required = '';

    /**
     * The fields type, will be added in <input type="" ...
     *
     * @var string
     */
    protected $type = 'text';

    /**
     * The javascript events bound to the field
     *
     * @var array
     */
    protected $events = array();

    /**
     * The javascript events bound to the label
     * @var array
     */
    protected $events_label = array();

    protected static $templates;

    protected static $config;

    /**
     * @var \Rocket\UI\Script\JS
     */
    protected static $js;

    /**
     * @var callable
     */
    protected static $js_resolver;

    /**
     * @var \Rocket\UI\Forms\Validators\ValidatorInterface
     */
    protected static $validator;

    /**
     * @var callable
     */
    protected static $validator_resolver;

    /**
     * Initializes the field
     * @param string $name
     * @param array $data
     */
    public function __construct($name, $data = array())
    {
        $this->name = $name;

        //valeurs par défaut
        $default = $this->getDefaults() + ['value' => set_value($name), 'required' => form_field_required($name)];

        //Get the new values
        $this->params = array_replace_recursive($default, $data);
    }

    /**
     * Set the javascript queueing instance callback
     *
     * @param $callable callable
     */
    public static function setJSResolver($callable)
    {
        self::$js_resolver = $callable;
    }

    /**
     * Get the Javascript queueing instance
     *
     * @return \Rocket\UI\Script\JS
     * @throws \Exception
     */
    protected function getJS()
    {
        if (null === self::$js) {
            if (!self::$js = call_user_func(self::$js_resolver)) {
                throw new \Exception('No javascript queueing instance can be found');
            }
        }

        return self::$js;
    }

    /**
     * Set the Form Validator instance callback
     *
     * @param $callable callable
     */
    public static function setValidatorResolver($callable)
    {
        self::$validator_resolver = $callable;
    }

    /**
     * Get the Javascript queueing instance
     *
     * @return \Rocket\UI\Forms\Validators\ValidatorInterface
     * @throws \Exception
     */
    protected function getValidator()
    {
        if (null === self::$validator) {
            if (!self::$validator = call_user_func(self::$validator_resolver)) {
                throw new \Exception('No javascript queueing instance can be found');
            }
        }

        return self::$validator;
    }

    /**
     * Get the default values array
     *
     * @return array
     */
    protected function getDefaults()
    {
        $defaults = array(
            'title' => '',
            'live' => 'blur',
            'validate' => true,
            'label_position' => 'before',
            'inline' => false, //afficher le label sur la même ligne que le champ
            'type' => $this->type,
            'width' => 0,
            'height' => 12,
            'margins' => 12, //width in px
            'data_attributes' => array(),
            'class' => '',
            'multifield' => false,
            'maxlength' => array(
                'slider' => true
            ),
        );

        return $defaults;
    }

    /**
     * Proper destructor
     */
    public function __destruct()
    {
        unset($this->name);
        unset($this->input_attributes);
        unset($this->label_attributes);
        unset($this->params);
        unset($this->required);
        unset($this->result);
        unset($this->show_label);
        unset($this->span_attributes);
    }

    /**
     * Adds a jquery event to the field
     * @param string $name
     * @param string $action
     * @param bool $bind_to_attributes
     * @return $this
     */
    public function event($name, $action, $bind_to_attributes = false)
    {
        if ($bind_to_attributes) {
            $this->input_attributes[$name] = $action;
        } else {
            $this->events[$name] = $action;
        }

        return $this;
    }

    /**
     * Adds a jquery event to the field
     * @param string $name
     * @param string $action
     * @return $this
     */
    public function eventLabel($name, $action)
    {
        $this->events_label[$name] = $action;

        return $this;
    }

    /**
     * Change parameters
     *
     * @param $method
     * @param $arguments
     * @return $this
     *
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (strlen(ltrim($method, '_')) != strlen($method)) {
            throw new \Exception('Invalid method');
        } else {
            if (is_array($arguments[0])) {
                if (array_key_exists($method, $this->params)) {
                    $this->params[$method] =
                        array_merge($this->params[$method], $arguments[0]);
                } else {
                    $this->params[$method] = $arguments[0];
                }
            } else {
                $this->params[$method] = $arguments[0];
            }

            return $this;
        }
    }

    /**
     * Set data attributes to the field
     *
     * @param $key string
     * @param $value string|array
     * @return $this
     */
    public function data($key, $value)
    {
        $this->params['data_attributes'][$key] = $value;

        return $this;
    }

    /**
     * Checks if it's an error
     */
    protected function hasError()
    {
        if ($this->getValidator()->hasError($this->name)) {
            //$this->input_attributes['class'][] = 'error';
            $this->label_attributes['class'][] = 'has-error';
        }
    }

    /**
     * Is required ?
     */
    protected function isRequired()
    {
        if ($this->params['required']
            && $this->show_label
            && $this->params['title'] != ''
        ) {
            $this->required = ' *';
        }
    }

    /**
     * Adds default css classes
     */
    protected function classes()
    {
        //Label
        if (array_key_exists('label_style', $this->params)) {
            $this->label_attributes['style'] = $this->params['label_style'];
        }

        if (array_key_exists('label_id', $this->params)) {
            $this->label_attributes['id'] = $this->params['label_id'];
        }

        $this->label_attributes['class']['form_type'] = 'form_' . $this->params['type'];

        //Input
        if (array_key_exists('input_style', $this->params)) {
            $this->input_attributes['style'] = $this->params['input_style'];
        }

        if (array_key_exists('class', $this->params)) {
            $this->input_attributes['class'][] = $this->params['class'];
        }

        $this->input_attributes['class']['type'] = 'form_' . $this->params['type'];
    }

    /**
     * Adds the width to the field
     */
    protected function width()
    {
        if (strpos($this->params['width'], '%') or
            strpos($this->params['width'], 'em') or
            strpos($this->params['width'], 'px')
        ) {
            $this->label_attributes['style']['width'] = $this->params['width'];

            if (strpos($this->params['width'], 'px')) {
                $w = str_replace('px', '', $this->params['width']);
                $w -= $this->params['margins'];
                $this->params['width'] = $w . 'px';
            }
            $this->input_attributes['style']['width'] = $this->params['width'];
            return;
        }

        if ($this->params['width'] != 0) {
            $this->label_attributes['class'][] = 'col-xs-' . $this->params['width'];
            return;
        }

        if ($this->params['width'] == 0) {
            $this->label_attributes['class'][] = 'col-full';
            return;
        }
    }

    /**
     * Extracts the validation rules to make a javascript validation
     */
    protected function inputValidation()
    {
        if (!$this->params['validate']) {
            return;
        }

        $field = $this->getValidator()->getRules($this->name);
        if (!empty($field)) {
            $this->input_attributes['data-rules'] = $field;
            $this->input_attributes['data-display'] = strip_tags($this->params['title']);
            $this->input_attributes['data-live'] = $this->params['live'];
        }
    }

    /**
     * generate an unique id to use in the field
     */
    protected function generateId()
    {
        $s = strtoupper(md5(uniqid(rand(), true)));

        return substr($s, 0, 8) . '_' . substr($s, 8, 4) . '_' . substr($s, 12, 4) . '_' . substr($s, 16, 4);
    }

    public function template($string, array $args = array())
    {
        $class = 'Form_Templates_Bootstrap';
        if (null == self::$templates) {
            self::$templates[$class] = get_class_vars($class);
        }

        //if there are some arguments
        if (empty($args)) {
            return self::$templates[$class][$string];
        }

        return strtr(self::$templates[$class][$string], $args);
    }

    /**
     * Renders the field
     * @return string
     */
    public function render()
    {
        if (!array_key_exists('id', $this->params) || empty($this->params['id'])) {
            $this->params['id'] = $this->generateId();
        }

        $this->id = $this->params['id'];

        //Run the scripts and events before (they may add some parameters)
        $this->renderEvents();
        $this->renderScript();

        $this->input_attributes['value'] = $this->params['value'];

        $this->classes();
        $this->width();

        $this->isRequired();
        $this->hasError();

        $this->inputAttributes();
        $this->inputDataAttributes();
        $this->inputValidation();
        $this->inputTip();

        if ($this->show_label) {
            $tag = $this->params['multifield'] ? 'div' : 'label';
            $this->result .= "<$tag " . $this->renderAttributes($this->label_attributes) . '>';
        }

        if ($this->params['label_position'] == 'before') {
            $this->renderTitle();
        }

        $this->renderInner();

        if ($this->params['label_position'] != 'before') {
            $this->renderTitle();
        }

        if (form_field_error($this->name)) {
            $this->result .= $this->template('HELP_BLOCK', ['!help' => form_field_get_error($this->name)]);
        }

        if ($this->show_label) {
            $this->result .= "</$tag>";
        }

        return $this->result;
    }

    /**
     * Auto render the field
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Renders the script
     */
    protected function renderScript()
    {
        if (!empty($this->params['maxlength']['maxCharacters'])) {
            $this->getJS()->ready('$("#' . $this->id . '").maxlength(' . json_encode($this->params['maxlength']) . ');');
        }
    }

    /**
     * Renders the javascript events
     */
    protected function renderEvents()
    {
        if (count($this->events) or count($this->events_label)) {

            if (count($this->events)) {
                foreach ($this->events as $event => $action) {
                    $this->getJS()->ready('$("#' . $this->id . '").' . $event . '(function(){ ' . $action . ' });');
                }
            }

            if (count($this->events_label)) {
                foreach ($this->events_label as $event => $action) {
                    $this->getJS()->ready('$("#' . $this->id . '").' . $event . '(function(){ ' . $action . ' });');
                }
            }
        }
    }

    /**
     * Renders the title
     */
    protected function renderTitle()
    {
        if ($this->show_label) {
            $this->result .= '<span' . $this->renderAttributes($this->span_attributes) . '>';
            if (array_key_exists('label_style', $this->params) && $this->params['label_style'] == 'small') {
                $this->result .= '<small>';
            }
            $this->result .= $this->params['title'] . $this->required;
            if (array_key_exists('label_style', $this->params) && $this->params['label_style'] == 'small') {
                $this->result .= '</small>';
            }
            $this->result .= '</span>';

            if ($this->params['inline'] == false && $this->params['title'] != ''
                && $this->params['label_position'] == 'before'
            ) {
                $this->result .= '<br />';
            }
        }
    }

    /**
     * Render the inner field
     */
    protected function renderInner()
    {
        $this->result .= '<input' . $this->renderAttributes($this->input_attributes) . ' />';
    }

    /**
     * Renders the input attributes
     */
    protected function inputAttributes()
    {
        $this->input_attributes['name'] = $this->name;
        $this->input_attributes['id'] = $this->id;
        $this->input_attributes['type'] = $this->params['type'];

        if (array_key_exists('enabled', $this->params)
            && $this->params['enabled'] == false
        ) {
            $this->input_attributes['disabled'] = 'disabled';
            $this->input_attributes['class'][] = 'disabled';
        }

        if (array_key_exists('writable', $this->params)
            && $this->params['writable'] == false
        ) {
            $this->input_attributes['readonly'] = 'readonly';
            $this->input_attributes['class'][] = 'readonly';
        }

        if (array_key_exists('onchange', $this->params)) {
            $this->input_attributes['onchange'] = $this->params['onchange'];
        }

        if (array_key_exists('placeholder', $this->params)) {
            $this->input_attributes['placeholder'] = $this->params['placeholder'];
        }
    }

    protected function labelAttributes()
    {
        $this->label_attributes['for'] = $this->id;
    }

    /**
     * Take all data attributes and render them to the input item
     */
    protected function inputDataAttributes()
    {
        foreach ($this->params['data_attributes'] as $key => $value) {
            $this->input_attributes['data-' . $key] = $value;
        }
    }

    /**
     * Renders the input tip
     */
    protected function inputTip()
    {
        if (isset($this->params['tip']) && $this->params['tip'] != '') {
            $this->input_attributes['title'] = json_encode($this->params['tip']);
        }
    }

    /**
     * Renders the special attributes
     *
     * @param  array $attr
     * @return string
     */
    protected function renderAttributes($attr)
    {
        if (array_key_exists('class', $attr) && is_array($attr['class'])) {
            $attr['class'] = implode(' ', $attr['class']);
        }

        if (array_key_exists('style', $attr) && is_array($attr['style'])) {
            $out = '';
            foreach ($attr['style'] as $key => $value) {
                $out .= $key . ':' . $value . '; ';
            }
            $attr['style'] = $out;
        }

        $out = ' ';
        foreach ($attr as $key => $value) {
            $out .= $key . '="' . $value . '" ';
        }

        return $out;
    }
}
