# Tables

[![Latest Version](https://img.shields.io/github/release/RocketPropelledTortoise/ui.svg?style=flat-square)](https://github.com/RocketPropelledTortoise/ui/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/rocket/ui/blob/master/LICENSE.md)
[![Build Status](https://img.shields.io/travis/RocketPropelledTortoise/UI/master.svg?style=flat-square)](https://travis-ci.org/RocketPropelledTortoise/UI)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/RocketPropelledTortoise/UI.svg?style=flat-square)](https://scrutinizer-ci.com/g/rocket/ui/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/RocketPropelledTortoise/UI.svg?style=flat-square)](https://scrutinizer-ci.com/g/rocket/ui)
[![Total Downloads](https://img.shields.io/packagist/dt/rocket/ui.svg?style=flat-square)](https://packagist.org/packages/rocket/ui)

This library is a helper to generate HTML tables from an array.

## Install

Via Composer

``` bash
$ composer require rocket/table
```

## Usage

``` php
$heads = ['Title', 'Author'];
$content = [
    ['The book I didn\'t write', 'Not Me']
    ['The book he wrote', 'It was me']
];

echo Table::quick($heads, $content);
```

will produce

``` html
<table class="table table-striped sticky-enabled">
    <thead>
        <tr><th>Title</th><th>Author</th></tr>
    </thead>
    <tbody>
        <tr><td>The book I didn't write</td><td>Not Me</td></tr>
        <tr><td>The book he wrote</td><td>It was me</td></tr>
    </tbody>
</table>
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
