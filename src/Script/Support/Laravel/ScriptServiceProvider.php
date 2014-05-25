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
