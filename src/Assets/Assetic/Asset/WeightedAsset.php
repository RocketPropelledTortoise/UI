<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 20.03.14
 * Time: 22:42
 */

namespace Rocket\UI\Assets\Assetic\Asset;


interface WeightedAsset {

    /**
     * @return integer
     */
    public function getWeight();

    /**
     * @param integer $weight
     * @return $this
     */
    public function setWeight($weight);
}
