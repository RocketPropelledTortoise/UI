<?php namespace Rocket\UI\Assets\Assetic\Asset;

use Assetic\Asset\FileAsset;

class WeightedAsset extends FileAsset
{
    /**
     * @var int
     */
    protected $weight = 0;

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }
}
