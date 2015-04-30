<?php namespace Rocket\UI\Script\Support\Laravel5;

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
