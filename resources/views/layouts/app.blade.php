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

    <script>
      window.App = {!! json_encode([
        'user' => Auth::user()
      ]) !!};
    </script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('after_style')
</head>
<body>
    <div id="app">

      @yield('profile')

      @yield('content')

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
