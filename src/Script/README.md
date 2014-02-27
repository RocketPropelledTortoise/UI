# Script queuing

[![Build Status](https://travis-ci.org/onigoetz/script.png?branch=master)](http://travis-ci.org/onigoetz/script) [![Coverage Status](https://coveralls.io/repos/onigoetz/script/badge.png)](https://coveralls.io/r/onigoetz/script)

In modern web applications, script execution should be done at the end of a page's loading.
This class is a very simple wrapper for this: queue scripts and render them when needed

## Requirements

- PHP 5.3

## Works perfectly with

### Laravel 4

- ServiceProvider : `Rocket\UI\Script\Support\Laravel\ScriptServiceProvider`
- Alias : `'JS' => 'Rocket\UI\Script\Support\Laravel\ScriptFacade'`
