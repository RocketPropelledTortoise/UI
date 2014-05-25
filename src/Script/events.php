<?php

use Assetic\AssetManager;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;

Event::listen(
    'rocket.assets.js',
    function (AssetManager $am) {
        $am->set('script_framework', (new JsAsset(__DIR__ . '/assets/js/framework.js'))->setWeight(-50));
    }
);
