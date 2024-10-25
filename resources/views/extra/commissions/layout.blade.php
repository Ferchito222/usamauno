<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Comisiones</title>

    @if(! isset($export))
        <style>
            #caja_arriba {
                background-color: {{ $info[ 'bg' ][ request()->get('type') ][ 1 ] }};
                color: #ffffff;
                width: 250px;
                padding: 10px;
                vertical-align: middle;
                font-family: Arial;
                /*border-top-right-radius: 24px;
                border-top-left-radius: 24px;*/
                /*para Firefox*/
                -moz-border-radius: 24px 24px 0 0;
                /*para Safari y Chrome*/
                -webkit-border-radius: 24px 24px 0 0;
                /* para Opera */
                border-radius: 24px 24px 0 0;
                /* para IE */
                behavior: url(border.htc);
            }

            #caja_abajo {
                background-color: {{ $info[ 'bg' ][ request()->get('type') ][ 2 ] }};
                color: #ffffff;
                width: 250px;
                padding: 10px;
                vertical-align: middle;
                font-family: Arial;
                font-size: 9pt;
                text-align: center;
                /*para Firefox*/
                -moz-border-radius: 0 0 24px 24px;
                /*para Safari y Chrome*/
                -webkit-border-radius: 0 0 24px 24px;
                /* para Opera */
                border-radius: 0 0 24px 24px;
                /* para IE */
                behavior: url(border.htc);
            }
        </style>
    @endif
</head>
<body ng-app="sibas" ng-controller="ExtraController">

@if(! isset($export))
    <form action="{{ request()->fullUrl() }}" method="post" target="_blank" id="export">
        {{ csrf_field() }}

        <input type="hidden" name="export" value="{{ sha1(1) }}"/>

        <table border="0" width="100%">
            <tr>
                <td>
                    <div id="caja_arriba">
                        <a href="#" style="vertical-align:middle; text-decoration: none;">
                            <img src="{{ asset('images/icon_excel.png') }}" width="50" ng-click="exportXls($event)"
                                 style="vertical-align:middle; border: 2px solid #FFF;"/>
                        </a>
                        <span style="font-weight: bold;">Comisiones Ecoseguros</span>
                    </div>
                    <div id="caja_abajo"> Reporte {{ $info[ 'bg' ][ request()->get('type') ][ 3 ] }}</div>
                </td>
                <td style="text-align:right;" align="center">
                    <img src="{{ asset('images/ecovida.png') }}" alt="">
                    <img src="{{ asset('images/ecoproteccion.png') }}" alt="">
                    <img src="{{ asset('images/crediseguro.png') }}" alt="">
                </td>
            </tr>
        </table>
    </form>
@endif

@yield('content')

@if(! isset($export))
    {!! Html::script('assets/js/core/libraries/jquery.min.js') !!}
    {!! Html::script('js/app.js') !!}
@endif
</body>
</html>