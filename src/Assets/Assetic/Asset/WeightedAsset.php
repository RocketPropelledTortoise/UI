<?php namespace Rocket\UI\Assets\Assetic\Asset;

use Assetic\Asset\FileAsset;

class WeightedAsset extends FileAsset {

    /**
     * @var int
     */
    protected $weight = 0;

    /**
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param integer $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }
}
