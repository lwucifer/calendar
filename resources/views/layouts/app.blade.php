<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CalendarSystem') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark navbar-laravel bg-dark text-white">
        <div class="container-fluid">
            <a class="navbar-brand logo" href="{{ url('/') }}">
                <img height="25" alt="logo" src="{{ url('/images/logo.png') }}"/>
                <span style="vertical-align: middle">{{ config('app.name', 'CalendarSystem') }}</span>
            </a>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a id="navbarDropdown" class="nav-link" href="./#/account/profile" role="button">
                            <span>{{ Auth::user()->first_name }} {{Auth::user()->last_name}}様</span>
                        </a>
                        <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}"/>
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="refreshStorage()">
                            {{ __('ログアウト') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
    @guest
    <div id="app">
        <main class="py-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>
    </div>
    @else
        @yield('content')
    @endguest
    <footer id="footer" class="bg-dark pt-2 pb-2">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <span class="text-white">Copyright 2019</span>
            </div>
        </div>
    </footer>
    <script type="text/javascript">
        function refreshStorage() {
            event.preventDefault();
            localStorage.removeItem('USER_INFO');
            localStorage.removeItem('CHECK_REDIRECT_LOGIN');
            document.getElementById('logout-form').submit();
        }

    </script>
</body>
</html>
