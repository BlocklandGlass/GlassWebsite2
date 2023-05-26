<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
    <head>
        <title>@yield('title') | {{ config('app.name') }}</title>

        <meta name="description" content="@yield('description', 'An open-source Blockland content service.')" />
        <meta name="theme-color" content="#2ecc71" />
        <meta name="robots" content="index,follow" />
        <meta name="og:site_name" content="{{ config('app.name') }}" />
        <meta name="og:title" content="@yield('title')" />
        <meta name="og:description" content="@yield('description', 'An open-source Blockland content service.')" />
        <meta name="og:type" content="website" />
        <meta charset="utf-8" />

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('css/flexboxgrid.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        @yield('head')
    </head>
    <body>
        <div id="container">
            <div class="head">
                <strong>🗞️ <a href="{{ route('news', ['2023-05-21'], false) }}">2023-05-21: Yes, we're back. No, you can't login yet</a></strong>
            </div>
            <div class="nav">
                <a href="{{ route('home', [], false) }}" id="logoBtn">
                    <img src="{{ asset('img/logoWhite.webp') }}" title="Blockland Glass" alt="The Blockland Glass logo in white" />
                </a>
                <ul>
                    <li><a href="{{ route('home', [], false) }}" class="navBtn">Home</a></li><li><a href="{{ route('addons', [], false) }}" class="navBtn">Add-Ons</a></li>
                </ul>
                @if (!auth()->user())
                    <a href="{{ route('login', [], false) }}" id="loginBtn">
                        <img src="{{ asset('img/sits_01.webp') }}" title="Sign in through Steam" alt="A sign in through Steam button" />
                    </a>
                @else
                    <a href="{{ route('logout', [], false) }}" id="userBtn">
                        <img src="{{ auth()->user()->avatar_url }}" title="Logout" alt="Your Steam avatar" />
                    </a>
                @endif
            </div>
            @hasSection('subNav')
                <div class="subNav">
                    @yield('subNav')
                </div>
            @endif
            <div class="content">
                @yield('content')
            </div>
        </div>
    </body>
</html>
