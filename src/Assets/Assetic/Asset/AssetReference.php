<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 25.05.14
 * Time: 17:40
 */

namespace Rocket\UI\Assets\Assetic\Asset;

use Assetic\AssetManager;
use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * A reference to an asset in the asset manager.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class AssetReference implements AssetInterface
{
    private $am;
    private $name;
    private $filters = array();

    public function __construct(AssetManager $am, $name)
    {
        $this->am = $am;
        $this->name = $name;
    }

    public function ensureFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    public function getFilters()
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__);
    }

    public function clearFilters()
    {
        $this->filters = array();
        $this->callAsset(__FUNCTION__);
    }

    public function load(FilterInterface $additionalFilter = null)
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__, array($additionalFilter));
    }

    public function dump(FilterInterface $additionalFilter = null)
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__, array($additionalFilter));
    }

    public function getContent()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function setContent($content)
    {
        $this->callAsset(__FUNCTION__, array($content));
    }

    public function getSourceRoot()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function getSourcePath()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function getTargetPath()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function setTargetPath($targetPath)
    {
        $this->callAsset(__FUNCTION__, array($targetPath));
    }

    public function getLastModified()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function getVars()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function getValues()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function setValues(array $values)
    {
        $this->callAsset(__FUNCTION__, array($values));
    }

    /**
     * @return \Assetic\Asset\AssetInterface
     */
    public function getAsset()
    {
       return $this->am->get($this->name);
    }

    // private
    private function callAsset($method, $arguments = array())
    {
        return call_user_func_array(array($this->getAsset(), $method), $arguments);
    }

    private function flushFilters()
    {
        $asset = $this->getAsset();

        while ($filter = array_shift($this->filters)) {
            $asset->ensureFilter($filter);
        }
    }
}
