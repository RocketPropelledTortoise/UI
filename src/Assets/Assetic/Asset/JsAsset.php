<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 20.03.14
 * Time: 22:44
 */

namespace Rocket\UI\Assets\Assetic\Asset;

use Assetic\Asset\FileAsset;


class JsAsset extends FileAsset implements WeightedAsset
{
    /**
     * @var int
     */
    protected $weight = 0;

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }
}
