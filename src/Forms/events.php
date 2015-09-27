<?php

use Assetic\Asset\AssetCollection;
use Assetic\AssetManager;
use Rocket\UI\Assets\Assetic\Asset\AssetReference;
use Rocket\UI\Assets\Assetic\Asset\CssAsset;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;

Event::listen(
    'rocket.assets.js',
    function (AssetManager $am) {

        $jsdir = __DIR__ . '/js/assets/';

        $am->set('forms::behaviors', new JsAsset($jsdir . 'behaviors.js'));
        $am->set('forms::callbacks', new JsAsset($jsdir . 'callbacks.js'));
        $am->set('forms::jquery.maxlength', new JsAsset($jsdir . 'jquery.maxlength.js'));
        $am->set('forms::tinyeditor', new JsAsset($jsdir . 'tinyeditor.js'));
        $am->set('forms::validate', new JsAsset($jsdir . 'validate.js'));

        $am->set('forms::pickadate_base', new JsAsset($jsdir . 'pickadate/picker.js'));
        $am->set('forms::pickadate_date', new JsAsset($jsdir . 'pickadate/picker.date.js'));
        $am->set('forms::pickadate_time', new JsAsset($jsdir . 'pickadate/picker.time.js'));

        $am->set(
            'forms::pickadate',
            new AssetCollection(
                [
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_date'),
                ]
            )
        );

        $am->set(
            'forms::pickatime',
            new AssetCollection(
                [
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_time'),
                ]
            )
        );

        $am->set(
            'forms::pickadatetime',
            new AssetCollection(
                [
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_date'),
                    new AssetReference($am, 'forms::pickadate_time'),
                ]
            )
        );
    }
);

Event::listen(
    'rocket.assets.css',
    function (AssetManager $am) {

        $cssdir = __DIR__ . '/css/assets/';

        $am->set('forms::pickadate_base', new CssAsset($cssdir . 'pickadate/classic.css'));
        $am->set('forms::pickadate_date', new CssAsset($cssdir . 'pickadate/classic.date.css'));
        $am->set('forms::pickadate_time', new CssAsset($cssdir . 'pickadate/classic.time.css'));

        $am->set(
            'forms::pickadate',
            new AssetCollection(
                [
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_date'),
                ]
            )
        );

        $am->set(
            'forms::pickatime',
            new AssetCollection(
                [
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_time'),
                ]
            )
        );

        $am->set(
            'forms::pickadatetime',
            new AssetCollection(
                [
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_date'),
                    new AssetReference($am, 'forms::pickadate_time'),
                ]
            )
        );
    }
);
