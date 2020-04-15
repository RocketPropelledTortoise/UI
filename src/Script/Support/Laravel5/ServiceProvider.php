<?php namespace Rocket\UI\Script\Support\Laravel5;

/**
 * Register special service providers
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
        $this->app->singleton(
            'js',
            function () {
                $js = new JS([]);
                $this->app['events']->dispatch('js.init', [$js]);

                return $js;
            }
        );
    }
}
