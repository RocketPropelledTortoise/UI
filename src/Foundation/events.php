<?php

use Assetic\Asset\AssetCollection;
use Assetic\AssetManager;
use Rocket\UI\Assets\Assetic\Asset\AssetReference;
use Rocket\UI\Assets\Assetic\Asset\CssAsset;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;

Event::listen(
    'rocket.assets.js',
    function (AssetManager $am) {
        $jsdir = __DIR__ . '/assets/js/';

        $am->set('foundation_jquery', (new JsAsset($jsdir . 'jquery-1.11.0.min.js'))->setWeight(-100));
        $am->set('foundation_bootstrap', (new JsAsset($jsdir . 'bootstrap-3.1.1.min.js'))->setWeight(-99));

        $am->set(
            'base',
            new AssetCollection(
                [
                    new AssetReference($am, 'script_framework'),
                    new AssetReference($am, 'foundation_jquery'),
                    new AssetReference($am, 'foundation_bootstrap'),
                ]
            )
        );
    }
);

Event::listen(
    'rocket.assets.css',
    function (AssetManager $am) {

        $cssdir = __DIR__ . '/assets/css/';
        $am->set('foundation_bootstrap', (new CssAsset($cssdir . 'bootstrap-3.1.1.min.css'))->setWeight(-99));

        $am->set(
            'base',
            new AssetCollection(
                [new AssetReference($am, 'foundation_bootstrap')]
            )
        );
    }
);
