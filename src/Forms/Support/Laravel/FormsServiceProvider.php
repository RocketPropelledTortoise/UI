<?php namespace Rocket\UI\Forms\Support\Laravel;

use Illuminate\Support\ServiceProvider;
use Rocket\UI\Forms\Fields\Field;
use Rocket\UI\Forms\Forms;
use Rocket\UI\Forms\Validators\CodeIgniterFormValidator;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app['config']->package('rocket/forms', __DIR__ . '/../../config', 'rocket_forms');
        Forms::setConfig($this->app['config']->get('rocket_forms::forms'));

        //By doing it like this we allow it to be lazily loaded into the forms
        Field::setJSResolver(
            function () {
                return $this->app->make('js');
            }
        );

        Field::setValidatorResolver(
            function () {
                return new CodeIgniterFormValidator();
            }
        );
    }
}
