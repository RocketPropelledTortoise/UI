# Script queuing

In modern web applications, script execution should be done at the end of a page's loading.
This class is a very simple wrapper for this: queue scripts and render them when needed

## Requirements

- PHP 5.3

## Works perfectly with

### Laravel 4

- ServiceProvider : `Rocket\UI\Script\Support\Laravel\ScriptServiceProvider`
- Alias : `'JS' => 'Rocket\UI\Script\Support\Laravel\ScriptFacade'`
