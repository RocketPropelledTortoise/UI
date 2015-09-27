<?php

namespace Rocket\UI\Assets;

use App;
use Assetic\Asset\AssetCache;
use Assetic\Asset\AssetInterface;
use Assetic\AssetManager;
use Assetic\Cache\FilesystemCache;
use Event;
use Response;
use Rocket\UI\Assets\Assetic\Asset\CssAsset;
use Rocket\UI\Assets\Assetic\WeightedAssetCollection;
use Rocket\Utilities\Format;

class Controller extends \BaseController
{
    /**
     * @param AssetManager $assetManager
     */
    public function __construct(AssetManager $assetManager)
    {
        $this->am = $assetManager;
    }

    protected function getCache($type)
    {
        return new FilesystemCache(app('path.storage') . '/cache/assets_' . $type);
    }

    public function css($files)
    {
        //Get the list of available assets
        Event::fire('rocket.assets.css', [$this->am]);

        $file_cache = $this->getCache('css');

        $assets = new WeightedAssetCollection();
        foreach (explode(',', $files) as $file) {
            if (!$this->am->has($file)) {
                return Response::make("CSS asset '$file' not found", 404);
            }

            $asset = $this->am->get($file);

            //Custom CSS assets need an access to the cache
            if ($asset instanceof CssAsset) {
                $asset->setCache($file_cache);
            }

            $assets->add($asset);
        }
        $css = new AssetCache($assets, $file_cache);

        return $this->prepareResponse($css, 'text/css; charset=utf-8');
    }

    public function js($files)
    {
        //Get the list of available assets
        Event::fire('rocket.assets.js', [$this->am]);

        $assets = new WeightedAssetCollection();
        foreach (explode(',', $files) as $file) {
            if (!$this->am->has($file)) {
                return Response::make("Javascript asset '$file' not found", 404);
            }

            $assets->add($this->am->get($file));
        }

        $js = new AssetCache($assets, $this->getCache('js'));

        return $this->prepareResponse($js, 'application/javascript; charset=utf-8');
    }

    protected function prepareResponse(AssetInterface $assets, $content_type)
    {
        $response = Response::stream(
            function () use ($assets) {
                echo $assets->dump();

                //Debug informations
                echo "\n/*  Memory usage: " . Format::getReadableSize(memory_get_usage(true));
                echo ', Peak usage: ' . Format::getReadableSize(memory_get_peak_usage(true));
                echo ', Elapsed time: ' . Format::getReadableTime((microtime(true) - LARAVEL_START) * 1000) . ' */';
            }
        );

        $response->headers->set('Content-type', $content_type);

        //TODO :: better way to choose to cache or not
        if (App::environment() == 'production') {
            $seconds_to_cache = 17280000; //200 days
            $time = time() + $seconds_to_cache;
            $response
                ->setExpires(new DateTime("@$time"))
                ->setMaxAge($seconds_to_cache)
                ->setPublic()
                ->setVary('Accept-Encoding');
        }

        return $response;
    }
}

//TODO :: move or remove strposa
function strposa($haystack, $needles = [], $offset = 0)
{
    $chr = [];
    foreach (array_keys($needles) as $needle) {
        $res = strpos($haystack, $needle, $offset);
        if ($res !== false) {
            $chr[$needle] = $res;
        }
    }
    if (empty($chr)) {
        return false;
    }

    return min($chr);
}
