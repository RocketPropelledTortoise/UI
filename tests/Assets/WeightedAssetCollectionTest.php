<?php

use Assetic\Asset\AssetInterface;
use PHPUnit\Framework\TestCase;
use Rocket\UI\Assets\Assetic\Asset\AssetReference;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;
use Rocket\UI\Assets\Assetic\WeightedAssetCollection;

class WeightedAssetCollectionTest extends TestCase
{
    public function testAssetOrderOnCreation()
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
        
        $resolved = array_values(
            array_map(
                function(AssetInterface $asset) {
                    return $asset->getSourceDirectory() . "/" . $asset->getSourcePath();
                },
                $collection->all()
            )
        );

        $this->assertEquals([
            'pickadate/picker.js',
            'pickadate/picker.date.js'
        ], $resolved);
    }

    public function testAssetOrderAdd()
    {
        $am = new \Assetic\AssetManager();

        $am->set('forms__pickadate_base', (new JsAsset('pickadate/picker.js'))->setWeight(-100));
        $am->set('forms__pickadate_date', new JsAsset('pickadate/picker.date.js'));

        $collection = new WeightedAssetCollection();

        $collection->add($am->get('forms__pickadate_date'));
        $collection->add($am->get('forms__pickadate_base'));
        
        $resolved = array_values(
            array_map(
                function(AssetInterface $asset) {
                    return $asset->getSourceDirectory() . "/" . $asset->getSourcePath();
                },
                $collection->all()
            )
        );

        $this->assertEquals([
            'pickadate/picker.js',
            'pickadate/picker.date.js'
        ], $resolved);
    }

    public function testAssetOrderMixed()
    {
        $am = new \Assetic\AssetManager();

        $am->set('forms__pickadate_base', (new JsAsset('pickadate/picker.js'))->setWeight(-100));
        $am->set('forms__pickadate_date', new JsAsset('pickadate/picker.date.js'));

        $collection = new WeightedAssetCollection(
            [
                new AssetReference($am, 'forms__pickadate_date')
            ]
        );

        $collection->add($am->get('forms__pickadate_base'));
        
        $resolved = array_values(
            array_map(
                function(AssetInterface $asset) {
                    return $asset->getSourceDirectory() . "/" . $asset->getSourcePath();
                },
                $collection->all()
            )
        );

        $this->assertEquals([
            'pickadate/picker.js',
            'pickadate/picker.date.js'
        ], $resolved);
    }
}
