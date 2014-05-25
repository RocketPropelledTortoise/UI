@extends('r_foundation::00_layout')

@section('title')
    {{ isset($title)? $title : '' }}
@stop

@section('style')
    {{ HTML::style(route('rocket.asset.css', ['' => 'base'])) }}
@stop

@section('script_footer')
    {{ HTML::script(route('rocket.asset.js', ['' => 'base'])) }}
    <script type="text/javascript">$(document).ready(APP.startApplication)</script>

    {{ JS::render() }}
@stop

@section('body')
    <nav class="navbar navbar-static-top navbar-inverse" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Rocket Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            @yield('nav')
        </div>
    </nav>

    @yield('before_content')

    <div id="page">
        <div id="content" class="cols">
            <h1>@yield('title')</h1>

            <div style="float:right">@yield('actions')</div>

            {{ Session::get('message') }}
            @yield('content')
        </div>
    </div>
@stop
