<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('after_style')
</head>
<body>
    <div id="app">
      <div class="container-fluid">
        <div class="row">
          <div id="sidebar" class="col-3 position-fixed">
            @yield('profile')

            @yield('activity')

            @yield('command')

          </div>
          <div class="col p-0">
            <nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="menu">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse text-center" id="navbarSupportedContent">
                        <!-- Main Of Navbar -->
                        <ul class="navbar-nav">
                            <!-- Authentication Links -->
                            @guest
                                <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                                <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                            @else
                                <li><a class="nav-link" href="#">PROJECTS</a></li>
                                <li><a class="nav-link" href="#">USERS</a></li>
                                <li><a class="nav-link" href="#">HELP</a></li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                                <li><a  class="nav-link" href="#" id="menu_off"><img src="{{ asset('images/menu_off.png') }}" alt=""></a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="p-0">
                @yield('content')
            </main>
          </div>

        </div>
      </div>

      @yield('selection')
      @yield('bottom')

    </div>

    <div id="sidebar-open">
      <a href="#" id="tools_on"><img src="{{ asset('images/tools_on.png') }}" alt=""></a>
    </div>

    <div id="menu-open">
      <a href="#" id="menu_on"><img src="{{ asset('images/menu_on.png') }}" alt=""></a>
    </div>


</body>
@yield('after_script')
</html>
