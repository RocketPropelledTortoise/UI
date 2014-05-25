# Laravel 4 Asset management

In any web application you need to load Javascript and CSS files to work.
With more and more plugins, parts and modules, you need an optimal way to load them.

Require JS tecniques are now the de-facto standard to load javascript. But I will bring it a step further, this is a require JS backend.

## How it works

You declare your assets on the backend, jquery, bootstrap, whatever..., declare their dependencies this would give for example:

    ++ bootstrap.popover -> bootstrap.popover.min.js
    |++ bootstrap.tooltip -> bootstrap.tooltip.min.js
    | +- jquery -> jquery.min.js
    ++ jquery.maxlength -> jquery.maxlength.js
     +- jquery -> jquery.min.js

If you have these libraries declared, you can load them this way:

- `http://server/_assets/jquery.js` -> this loads jQuery only
- `http://server/_assets/bootstrap.tooltip.js` -> this loads jQuery and bootstrap.tooltip (in that order)
- `http://server/_assets/bootstrap.popover,jquery.maxlength.js` -> this loads jquery, bootstrap.tooltip, bootstrap.popover, jquery.maxlength

This works the same way with CSS :
- `http://server/_assets/bootstrap.css`
- `http://server/_assets/bootstrap.theme.css`

## What it is not

This asset manager will not provide server side support to generate/minify your `less`, `sass`, `coffeescript` or other languages, other tools like `grunt` or `gulp` are better suited for that task.

The asset manager's role is only to concatenate and serve the assets, but if you want to do it, you can, as we depend on Kris Wallsmith's Assetic package

## Service Provider

`Rocket\UI\Assets\ServiceProvider`

## Usage

### Declare a library

To declare a library you have to listen for the `rocket.assets.js` and `rocket.assets.css` events.

Here is an example taken from a Rocket Library

```php
use Assetic\AssetManager;
use Assetic\Asset\AssetCollection;
use Rocket\UI\Assets\Assetic\Asset\AssetReference;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;

Event::listen(
    'rocket.assets.js',
    function (AssetManager $am) {

        $jsdir = __DIR__ . '/js/assets/';

        $am->set('forms::pickadate_base', new JsAsset($jsdir . 'pickadate/picker.js'));
        $am->set('forms::pickadate_date', new JsAsset($jsdir . 'pickadate/picker.date.js'));
        $am->set('forms::pickadate_time', new JsAsset($jsdir . 'pickadate/picker.time.js'));


        $am->set(
            'forms::pickadate',
            new AssetCollection(
                array(
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_date'),
                )
            )
        );

        $am->set(
            'forms::pickatime',
            new AssetCollection(
                array(
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_time'),
                )
            )
        );

        $am->set(
            'forms::pickadatetime',
            new AssetCollection(
                array(
                    new AssetReference($am, 'forms::pickadate_base'),
                    new AssetReference($am, 'forms::pickadate_date'),
                    new AssetReference($am, 'forms::pickadate_time'),
                )
            )
        );
    }
);
```

### Libraries loading order

Each asset has a `weight` property, with higher values; the asset sinks, with lower values; the asset rises.

```php
        $am->set('foundation_jquery', (new JsAsset($jsdir . 'jquery-1.11.0.min.js'))->setWeight(-100));
        $am->set('foundation_bootstrap', (new JsAsset($jsdir . 'bootstrap-3.1.1.min.js'))->setWeight(-99));

```

In this case, jquery has a weight of -100 (so it will be loaded first) and bootstrap has a weight of -99

### Change an asset

When you add an element to the Asset manager, it is simply by key => value, so if you call `set` again with the same key but another asset it will replace it and when you require it will take your file instead of the default one.

This can be very useful if for some special case, you need to load a different version of jQuery or you made a custom Twitter Bootstrap base theme.

By getting the asset you can also change it directly. here are some examples.


```php
		//change asset
        $am->set('foundation_jquery', (new JsAsset($jsdir . 'jquery-2.0.0.min.js'))->setWeight(-100));

        //change weight
        $am->get('foundation_bootstrap')->setWeight(-99);

```

## `//Todo`

This package is usable but still in heavy development, here are some features that need to be added

- Support real dependencies declaration, for the moment we create packages that combine the libraries
- Unit tests
- Performance tests
- Client side JS loaded, might be in the `script` package in the end
- Provide more options to declare "already loaded" parts (say gimme bootstrap.tooltip, but not jQuery)
- Test CSS imports
