<?php namespace Rocket\UI\Forms\Support\Laravel5;

use Rocket\UI\Forms\Fields\Field;
use Rocket\UI\Forms\Forms;
use Rocket\UI\Forms\Validators\CodeIgniterFormValidator;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->publishes(
            [
                __DIR__ . '/config.php' => config_path('rocket_ui_forms.php'),
            ]
        );

        Forms::setConfig($this->app['config']->get('rocket_ui_forms'));

        //By doing it like this we allow it to be lazily loaded into the forms
        Field::setJSResolver(
            function () {
                return $this->app->make('js');
            }
        );
    }
}
