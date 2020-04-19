# Forms, simplified

[![Latest Version](https://img.shields.io/github/release/RocketPropelledTortoise/UI.svg?style=flat-square)](https://github.com/RocketPropelledTortoise/UI/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/RocketPropelledTortoise/UI/blob/master/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/rocket/ui.svg?style=flat-square)](https://packagist.org/packages/rocket/ui)
[![Sonar Quality Gate](https://img.shields.io/sonar/alert_status/RocketPropelledTortoise_UI?server=https%3A%2F%2Fsonarcloud.io&style=flat-square)](https://sonarcloud.io/dashboard?id=RocketPropelledTortoise_UI)
[![Sonar Coverage](https://img.shields.io/sonar/coverage/RocketPropelledTortoise_UI?server=https%3A%2F%2Fsonarcloud.io&style=flat-square)](https://sonarcloud.io/dashboard?id=RocketPropelledTortoise_UI)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/RocketPropelledTortoise/UI/PHP?style=flat-square)](https://github.com/RocketPropelledTortoise/UI/actions)

Wrapper around form creation, also handles putting the value of a sent form, default values, and adding classes for errors.

The markup is made for Twitter Bootstrap version 3

## Install

Via Composer

``` bash
$ composer require rocket/forms
```

## Usage

### PHP

```php
echo Forms::email('email', 'Adresse E-mail')->width(6);
echo Forms::password('password', 'Mot de passe')->width(6);
echo Forms::checkbox('remember', 'Se souvenir de moi')->width(6);
```

### Blade

```
{{ Forms::email('email', 'Adresse E-mail')->width(6) }}
{{ Forms::password('password', 'Mot de passe')->width(6) }}
{{ Forms::checkbox('remember', 'Se souvenir de moi')->width(6) }}
```

### Twig
With the twig extension, it is very easy to create form fields with a fluid syntax

    {% form 'email' 'Adresse E-mail' 'email' width(6) %}
    {% form 'password' 'Mot de passe' 'password' width(6) %}
    {% form 'remember' 'Se souvenir de moi' 'checkbox' width(6) %}

## Testing

All the tests live in the [main project](https://github.com/rocket/ui).

## Contributing

Please see [CONTRIBUTING](https://github.com/rocket/ui/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Stéphane Goetz](https://github.com/onigoetz)
- [All Contributors](https://github.com/RocketPropelledTortoise/:package_name/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/rocket/ui/blob/master/LICENSE.md) for more information.
