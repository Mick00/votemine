<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEO::generate(true) !!}
    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}"></script>
    <script src="{{ mix('/js/manifest.js') }}"></script>
    <script src="text/javascript" src="https://cdn.jsdelivr.net/gh/thednp/minifill@0.0.4/dist/minifill.min.js"> </script>
    <!--[if IE]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cf1e1673fd.js" crossorigin="anonymous"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
@include('components.alerts-drawer')
    @if ($modalDatal = Session::get('modal'))
        <div id="modal" data-modal="{{$modalDatal}}"></div>
    @endif
    <div id="app">
        @include('menus.top-menu')
        @yield('content')
    </div>
</body>
</html>
