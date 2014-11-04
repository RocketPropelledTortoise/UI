# Forms, simplified

[![Latest Version](https://img.shields.io/github/release/RocketPropelledTortoise/ui.svg?style=flat-square)](https://github.com/RocketPropelledTortoise/ui/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/rocket/ui/blob/master/LICENSE.md)
[![Build Status](https://img.shields.io/travis/RocketPropelledTortoise/UI/master.svg?style=flat-square)](https://travis-ci.org/RocketPropelledTortoise/UI)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/RocketPropelledTortoise/UI.svg?style=flat-square)](https://scrutinizer-ci.com/g/rocket/ui/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/RocketPropelledTortoise/UI.svg?style=flat-square)](https://scrutinizer-ci.com/g/rocket/ui)
[![Total Downloads](https://img.shields.io/packagist/dt/rocket/ui.svg?style=flat-square)](https://packagist.org/packages/rocket/ui)

Wrapper around form creation, also handles putting the value of a sent form, default values, and adding classes for errors.

The markup is made for Twitter Bootstrap version 3

## Install

Via Composer

``` bash
$ composer require rocket/forms
```

## Usage

### PHP
A slightly better syntax is in preparation for PHP and Blde, but is not ready right now

``` php
    echo FE('email', 'Adresse E-mail, 'email')->width(6);
    echo FE('password', 'Mot de passe', 'password')->width(6);
    echo FE('remember', 'Se souvenir de moi', 'checkbox')->width(6);
```

### Blade
A slightly better syntax is in preparation for PHP and Blde, but is not ready right now

    {{ FE('email', 'Adresse E-mail, 'email')->width(6) }}
    {{ FE('password', 'Mot de passe', 'password')->width(6) }}
    {{ FE('remember', 'Se souvenir de moi', 'checkbox')->width(6) }}

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
