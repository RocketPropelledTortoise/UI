<?php namespace Rocket\UI\Assets\Support\Laravel5;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $this->app['router']->get(
            '_assets/{files}.css',
            ['as' => 'rocket.asset.css', 'uses' => 'Rocket\UI\Assets\Controller@css']
        );

        $this->app['router']->get(
            '_assets/{files}.js',
            ['as' => 'rocket.asset.js', 'uses' => 'Rocket\UI\Assets\Controller@js']
        );
    }
}
