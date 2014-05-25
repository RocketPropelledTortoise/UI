<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  {{-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/  --}}
  {{-- Consider adding a manifest.appcache: h5bp.com/d/Offline --}}
  {{-- Use the .htaccess and remove these lines to avoid edge case issues. More info: h5bp.com/i/378 --}}
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  @yield('meta')

  <title>Rocket CMS | @yield('title')</title>

  {{-- Mobile viewport optimized: h5bp.com/viewport --}}
  <meta name="viewport" content="width=device-width">

  {{-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons --}}

  @yield('style')

  {{-- More ideas for your <head> here: h5bp.com/d/head-Tips --}}

  {{-- All JavaScript at the bottom, except this Modernizr build.
       Modernizr enables HTML5 elements & feature detects for optimal performance.
       Create your own custom Modernizr build: www.modernizr.com/download/ --}}

  @yield('header')

  {{-- Maybe add HTML 5 shivs --}}

  @yield('script_header')
</head>
<body class="{{ body_classes() }} @yield('body_class')">
    @yield('body')

    @yield('script_footer')
</body>
</html>
