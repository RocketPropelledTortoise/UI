<?php
/**
 * Add service providers
 */

namespace Rocket\UI\Script\Support\Laravel;

use Illuminate\Support\ServiceProvider;

/**
 * Register special service providers
 *
 * @package Provider
 */
class ScriptServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $this->app['js'] = $this->app->share(
            function () use ($app) {
                $js = new JS(array());
                $app['events']->fire('js.init', array($js));
                return $js;
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
        return array('js');
    }
}
