<?php namespace Rocket\UI\Foundation\Support\Laravel5;

/**
 * Register special service providers
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    //TODO :: find a cleaner way to load events for all packages
    protected function loadEvents()
    {
        include __DIR__ . '/../../events.php';
    }

    protected function registerViewNamespace()
    {
        $package = 'rocket/foundation';
        $namespace = 'r_foundation';

        // Next, we will see if the application view folder contains a folder for the
        // package and namespace. If it does, we'll give that folder precedence on
        // the loader list for the views so the package views can be overridden.
        $appView = $this->getAppViewPath($package);
        if ($this->app['files']->isDirectory($appView)) {
            $this->app['view']->addNamespace($namespace, $appView);
        }

        $this->app['view']->addNamespace($namespace, __DIR__ . '/views');
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->registerViewNamespace();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->loadEvents();
    }
}
