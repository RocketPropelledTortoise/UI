<?php namespace Rocket\UI\Forms\Support\Laravel;

use Illuminate\Support\ServiceProvider;
use Rocket\UI\Forms\Fields\Field;
use Rocket\UI\Forms\Forms;
use Rocket\UI\Forms\Validators\CodeIgniterFormValidator;

class FormsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;
        $app['config']->package('onigoetz/forms', __DIR__ . '/../../config');
        Forms::setConfig($app['config']->get('forms::forms'));

        //By doing it like this we allow it to be lazily loaded into the forms
        Field::setJSResolver(
            function () use ($app) {
                return $app->make('js');
            }
        );

        Field::setValidatorResolver(
            function () use ($app) {
                return new CodeIgniterFormValidator();
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
