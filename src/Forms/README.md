# Easy Forms

Wrapper around form creation, also handles putting the value of a sent form, default values, and adding classes for errors.

The fields are created with Bootstrap 3's markup

## Required to work

- jQuery for some fields, provided by the `Foundation` and `Asset` packages

## Integrations

### Laravel

`Illuminate\Support\ServiceProvider\FormsServiceProvider`

### Twig

There is an extension for Twig to allow you to simply create forms with.

```php
$twig->addExtension(new Rocket\UI\Forms\Support\Twig\Extension());
``

## Available fields

A lot of fields are available by default, you can also add your own ones

- date - comes with a datepicker (pickadate)
- time - comes with a datepicker (pickadate)
- datetime - comes with a datepicker (pickadate)
- textarea - simple textarea
- htmlarea - WYSIWYG with tinyeditor
- text - text field
- password - password field
- radio - radio button
- email - email field
- submit - submit button
- autocomplete - autocomplete, works with a jQuery UI autocomplete replacement, supports multiple terms
- select - select box, supports groups
- checkbox
- hidden - hidden field
- file - file field, with special hack to be able to style the field

__ Only with Laravel 4__

- honeypot - honeypot, works only with Laravel
- kaptcha - ask for an easy math problem

## Usage

### PHP
A slightly better syntax is in preparation for PHP and Blde, but is not ready right now

    echo FE('email', t('Adresse E-mail), 'email')->width(6);
    echo FE('password', t('Mot de passe'), 'password')->width(6);
    echo FE('remember', t('Se souvenir de moi'), 'checkbox')->width(6);

### Blade
A slightly better syntax is in preparation for PHP and Blde, but is not ready right now

    {{ FE('email', t('Adresse E-mail), 'email')->width(6) }}
    {{ FE('password', t('Mot de passe'), 'password')->width(6) }}
    {{ FE('remember', t('Se souvenir de moi'), 'checkbox')->width(6) }}

### Twig
With the twig extension, it is very easy to create form fields with a fluid syntax

    {% form 'email' 'Adresse E-mail'|t 'email' width(6) %}
    {% form 'password' 'Mot de passe'|t 'password' width(6) %}
    {% form 'remember' 'Se souvenir de moi'|t 'checkbox' width(6) %}

## `//Todo`

- Finish Documentation
- Finish binding to Laravel Validator
- Unit tests
