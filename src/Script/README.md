# Script queueing

[![Latest Version](https://img.shields.io/github/release/RocketPropelledTortoise/UI.svg?style=flat-square)](https://github.com/RocketPropelledTortoise/UI/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/RocketPropelledTortoise/UI/blob/master/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/rocket/ui.svg?style=flat-square)](https://packagist.org/packages/rocket/ui)
[![Sonar Quality Gate](https://img.shields.io/sonar/alert_status/RocketPropelledTortoise_UI?server=https%3A%2F%2Fsonarcloud.io&style=flat-square)](https://sonarcloud.io/dashboard?id=RocketPropelledTortoise_UI)
[![Sonar Coverage](https://img.shields.io/sonar/coverage/RocketPropelledTortoise_UI?server=https%3A%2F%2Fsonarcloud.io&style=flat-square)](https://sonarcloud.io/dashboard?id=RocketPropelledTortoise_UI)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/RocketPropelledTortoise/UI/PHP?style=flat-square)](https://github.com/RocketPropelledTortoise/UI/actions)

In modern web applications, script execution should be done at the end of a page's loading.
This class is a very simple wrapper for this: queue scripts and render them when needed

## Install

Via Composer

``` bash
$ composer require rocket/script
```

### Service Provider

`\Rocket\UI\Script\Support\Laravel5\ServiceProvider`

### Middleware

(App\Http\Kernel.php)

`\Rocket\UI\Script\Support\Middleware\ScriptMiddleware`

## Usage

``` php
JS::ready(' console.log("you\'re a wizard, Harry!"); ');
JS::ready(' console.log("Dobby is freeeee!"); ');
```

Will produce

``` javascript
<script>
$(function() {
    console.log("you're a wizard, Harry!");

    //---

    console.log("Dobby is freeeee!");
});
</script>
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
