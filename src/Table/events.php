<?php

use Assetic\AssetManager;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;

Event::listen(
    'rocket.assets.js',
    function (AssetManager $am) {
        $am->set('table_fixedtableheader', (new JsAsset(__DIR__ . '/assets/js/jquery.fixedtableheader.js')));
    }
);
