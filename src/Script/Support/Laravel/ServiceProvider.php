<?php namespace Rocket\UI\Script\Support\Laravel;

/**
 * Register special service providers
 *
 * @package Provider
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->loadEvents();

        $this->shareJS();

        $this->registerMiddleware();
    }

    protected function registerMiddleware()
    {
        $this->app->middleware('\Rocket\UI\Script\Support\Middleware\ScriptMiddleware');
    }

    protected function loadEvents()
    {
        include dirname(dirname(__DIR__)) . '/events.php';
    }

    protected function shareJS()
    {
        $this->app['js'] = $this->app->share(
            function () {
                $js = new JS([]);
                $this->app['events']->fire('js.init', [$js]);
                return $js;
            }
        );
    }
}
