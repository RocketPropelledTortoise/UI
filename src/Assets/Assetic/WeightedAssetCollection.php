<?php namespace Rocket\UI\Assets\Assetic;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Rocket\UI\Assets\Assetic\Asset\AssetReference;
use Rocket\UI\Assets\Assetic\Asset\WeightedAsset;

class WeightedAssetCollection extends AssetCollection
{
    protected $orderable = [];
    protected $flattened = false;

    public function load(FilterInterface $additionalFilter = null)
    {
        $this->flattenAndOrderAssets();

        parent::load($additionalFilter);
    }

    public function dump(FilterInterface $additionalFilter = null)
    {
        $this->flattenAndOrderAssets();

        return parent::dump($additionalFilter);
    }

    public function all()
    {
        $this->flattenAndOrderAssets();
        return parent::all();
    }

    public function add(AssetInterface $asset)
    {
        parent::add($asset);

        $this->flattened = false;
    }

    protected function flattenAndOrderAssets()
    {
        if ($this->flattened) {
            return;
        }

        //recursively get assets
        $original = parent::all();
        foreach ($original as $asset) {
            $this->flatten($asset);
            $this->removeLeaf($asset);
        }

        //order
        ksort($this->orderable);

        //flatten
        foreach ($this->orderable as $weight) {
            foreach ($weight as $item) {
                $this->add($item);
            }
        }

        $this->flattened = true;
    }

    protected function flatten($asset)
    {
        if ($asset instanceof AssetCollection) {
            foreach ($asset->all() as $leaf) {
                $this->flatten($leaf);
            }

            return;
        }

        $weight = 0;
        if ($asset instanceof WeightedAsset) {
            $weight = $asset->getWeight();
        }

        if ($asset instanceof AssetReference) {
            $weight = $asset->getAsset()->getWeight();
        }
 
        $this->orderable[$weight][] = $asset;
    }
}
