# Foundation

A basic canvas for web applications, with lightweight Javascript framework, Asset Management and Bootstrap Templates

## How it works

When you create a laravel page, put `@extends('r_foundation::layout')` at the top, you will automatically use it.

## Usage

Note to self: Extend on that ...

## Sections

You can freely use sections in blade with the following names:

### <head>
- `meta` : add header metas
- `title` : the document's title
- `style` : if you want to add some styling rules
- `header` : anythig else that goes in the header
- `script_header` : if you want to add a script in the header

### <body>
- `body_class` - add one or more classes to the body
- `body` - entire body, should not be used
- `script_footer` -  the footer scripts
- `content` - put your content here
- `nav` - your menu here
- `actions` - add content related actions here
- `before_content` - special content before the main content

## `//Todo`

This package is usable but still in heavy development, here are some features that need to be added

- Automatic menu
