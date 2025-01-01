<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
    <head>
        <title>@yield('title') | {{ config('app.name') }}</title>

        <meta name="description" content="@yield('description', 'An open-source Blockland content service.')" />
        <meta name="theme-color" content="#2ecc71" />
        <meta name="og:site_name" content="{{ config('app.name') }}" />
        <meta name="og:title" content="@yield('title')" />
        <meta name="og:description" content="@yield('description', 'An open-source Blockland content service.')" />
        <meta name="og:type" content="website" />
        <meta charset="utf-8" />

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
        <link rel="stylesheet" href="{{ asset('css/flexboxgrid.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/boxicons.min.css') }}">

        @if (file_exists($path = public_path('css/app.min.css')))
            <link rel="stylesheet" href="{{ asset('css/app.min.css') }}?crc={{ hash_file('crc32b', $path) }}">
        @endif

        <meta name="viewport" content="width=device-width, initial-scale=1">

        @yield('head')
    </head>
    <body>
        <div id="container">
            <div class="header">
                <a href="{{ route('news', ['2023-05-21'], false) }}" id="newsBtn" class="link">2023-05-21: Yes, we're back. No, you can't login yet</a>
            </div>
            <div class="nav">
                <a href="{{ route('home', [], false) }}" id="logoBtn">
                    <img src="{{ asset('img/logoWhite.webp') }}" title="Blockland Glass" alt="The Blockland Glass logo in white" />
                </a>
                @if (! auth()->user())
                    <ul>
                        <li><a href="{{ route('home', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-home"></i>Home</a></li><li><a href="{{ route('addons', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-package"></i>Add-Ons</a></li>
                    </ul>
                    <a href="{{ route('login', [], false) }}" title="Login" id="loginBtn">
                        <i class="bx bx-md bxs-key"></i>
                    </a>
                @else
                    <ul>
                        <li><a href="{{ route('home', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-home"></i>Home</a></li><li><a href="{{ route('addons', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-package"></i>Add-Ons</a><li><a href="{{ route('my-account', [], false) }}" class="navBtn"><i class="bx-fw bx bxs-user-account"></i>My Account</a></li>
                    </ul>
                    <a href="{{ route('logout', [], false) }}" title="Logout" id="logoutBtn">
                        <i class="bx bx-md bxs-log-out"></i>
                    </a>
                @endif
            </div>
            @hasSection('subNav')
                <div class="subNav">
                    @yield('subNav')
                </div>
            @endif
            <div id="subContainer">
                @hasSection('breadcrumb')
                    <div class="breadcrumb">
                        @yield('breadcrumb')
                    </div>
                @endif
                <div class="content">
                    @yield('content')
                </div>
            </div>
            <div class="footer">
                @if (isset($revision))
                    <span>Running <a href="https://github.com/BlocklandGlass/GlassWebsite2" target="_blank" rel="noreferrer" class="link">GlassWebsite2</a> rev. {{ $revision }} (last updated {{ $revisionTime }})</span>
                @else
                    <span>Running <a href="https://github.com/BlocklandGlass/GlassWebsite2" target="_blank" rel="noreferrer" class="link">GlassWebsite2</a></span>
                @endif
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
        @yield('scripts')
    </body>
</html>
