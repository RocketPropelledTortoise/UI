# Laravel Asset management

[![Latest Version](https://img.shields.io/github/release/RocketPropelledTortoise/ui.svg?style=flat-square)](https://github.com/RocketPropelledTortoise/ui/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/rocket/ui/blob/master/LICENSE.md)
[![Build Status](https://img.shields.io/travis/RocketPropelledTortoise/UI/master.svg?style=flat-square)](https://travis-ci.org/RocketPropelledTortoise/UI)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/RocketPropelledTortoise/UI.svg?style=flat-square)](https://scrutinizer-ci.com/g/rocket/ui/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/RocketPropelledTortoise/UI.svg?style=flat-square)](https://scrutinizer-ci.com/g/rocket/ui)
[![Total Downloads](https://img.shields.io/packagist/dt/rocket/ui.svg?style=flat-square)](https://packagist.org/packages/rocket/ui)

__ !!! This package is in development and is not yet ready for prime time !!! __

In any web application you need to load Javascript and CSS files to work.
With more and more plugins, parts and modules, you need an optimal way to load them.

Require JS techniques are now the de-facto standard to load javascript. But I will bring it a step further, this is a require JS backend.

## Install

Via Composer

``` bash
$ composer require rocket/assets
```

## Usage

```php
use Assetic\AssetManager;
use Rocket\UI\Assets\Assetic\Asset\JsAsset;

Event::listen(
    'rocket.assets.js',
    function (AssetManager $am) {
        $am->set('jquery', new JsAsset(__DIR__ . '/js/jquery.js'));
        $am->set('jquery.tooltips', (new JsAsset(__DIR__ . '/js/jquery.tooltips.js'))->dependsOn('jquery'));
    }
);
```

## Testing

All the tests live in the [main project](https://github.com/rocket/ui).

## Contributing

Please see [CONTRIBUTING](https://github.com/rocket/ui/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Stéphane Goetz](https://github.com/onigoetz)
- [All Contributors](https://github.com/RocketPropelledTortoise/:package_name/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/rocket/ui/blob/master/LICENSE.md) for more information.
