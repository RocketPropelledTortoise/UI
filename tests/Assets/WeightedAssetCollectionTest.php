<?php

use Assetic\Asset\AssetReference;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;
use Rocket\UI\Assets\Assetic\WeightedAssetCollection;

class WeightedAssetCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testAssetOrder()
    {
        $am = new \Assetic\AssetManager();

        $am->set('forms__pickadate_base', (new JsAsset('pickadate/picker.js'))->setWeight(-100));
        $am->set('forms__pickadate_date', new JsAsset('pickadate/picker.date.js'));

        $collection = new WeightedAssetCollection(
            [
                new AssetReference($am, 'forms__pickadate_date'),
                new AssetReference($am, 'forms__pickadate_base'),
            ]
        );
    }
}
