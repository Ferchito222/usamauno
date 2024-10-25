<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Abrenet / Administrador</title>

    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<header class="Header">
    @var $menu = menuApp()

    <a href="{{ route($menu['adm']['home']) }}" class="Header-logo">Abrenet</a>

    <div class="Header-menuContainer">
        <nav class="menu">
            <ul class="clearfix">
                @foreach($menu['adm']['items'] as $item)
                    @var $routes = $item['subMenus']->pluck('route')->toArray();

                    <li class="{{ in_array($menu['currentRoute'], $routes) ? 'current-item' : '' }}">
                        @if(count($item['subMenus']) > 0)
                            <a href="#!">{{ $item['title'] }} <span class="arrow">&#9660;</span></a>

                            <ul class="sub-menu">
                                @foreach($item['subMenus'] as $subMenu)
                                    @if(count(array_intersect($subMenu['permission'], $menu['userPermissions'])) > 0)
                                        <li class="{{ $menu['currentRoute'] === $subMenu['route'] ? 'current-item' : '' }}">
                                            <a href="{{ route($subMenu['route']) }}">{{ $subMenu['title'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ route($item['route']) }}">{{ $item['title'] }}</a>
                        @endif
                @endforeach

                <li>
                    <a href="#">{{ auth()->user()->full_name }}</a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('adm::user.password.edit') }}">Cambiar contraseña</a></li>
                        <li><a href="{{ route('auth.logout') }}">Salir</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>

<div class="App">
    @yield('content')
</div>

<div id="Loader-wrapper">
    <div class="Sk-cubeGrid">
        <div class="Sk-cube Sk-cube1"></div>
        <div class="Sk-cube Sk-cube2"></div>
        <div class="Sk-cube Sk-cube3"></div>
        <div class="Sk-cube Sk-cube4"></div>
        <div class="Sk-cube Sk-cube5"></div>
        <div class="Sk-cube Sk-cube6"></div>
        <div class="Sk-cube Sk-cube7"></div>
        <div class="Sk-cube Sk-cube8"></div>
        <div class="Sk-cube Sk-cube9"></div>
    </div>
    Por favor espere...
</div>

<script src="{{ asset('assets/js/adm.js') }}"></script>
</body>
</html>