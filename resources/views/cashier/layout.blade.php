<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cajeros</title>

    {!! Html::style('assets/css/icons/fontawesome/styles.min.css') !!}
    {!! Html::style('assets/css/bootstrap-theme-2.min.css') !!}
    {!! Html::style('assets/css/panel.css') !!}
    {!! Html::style('css/jquery.loading.css') !!}
    {!! Html::style('assets/css/components.min.css') !!}

    {!! Html::script('assets/js/core/libraries/jquery.min.js') !!}
    {!! Html::script('assets/js/core/libraries/bootstrap.min.js') !!}
    {!! Html::script('assets/js/core/libraries/jquery_ui/datepicker.min.js') !!}

    {!! Html::script('js/jquery.loading.min.js') !!}
    {!! Html::script('js/jquery.PrintArea.js') !!}
</head>
<body ng-app="sibas">
<div class="navbar navbar-default" role="navigation">
    <div class="navbar-inner">
        <div class="btn-group pull-right">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-user"></i>
                <span class="hidden-sm hidden-xs">
                    {{ $cashier->full_name }}
                </span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                {{--<li><a href="#">Profile</a></li>
                <li class="divider"></li>--}}
                <li><a href="{{ route('auth.cashier.logout') }}">Salir</a></li>
            </ul>
        </div>

        <ul class="collapse navbar-collapse nav navbar-nav top-menu">
            <li>
                <a href="#"><i class="glyphicon glyphicon-globe"></i>
                    {!! Html::image($cashier->retailer->image, '', ['width' => '120']) !!}
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="ch-container">
    <div class="row">
        <div class="col-sm-2 col-lg-2">
            <div class="sidebar-nav">
                <div class="nav-canvas">
                    <div class="nav-sm nav nav-stacked">
                    </div>
                    <ul class="nav nav-pills nav-stacked main-menu" style="margin-top: 70px;">
                        @foreach($retailerProducts as $retailerProduct)
                            <li class="nav-header hidden-md" style="font-size: 100%;">
                                @if($retailerProduct->companyProduct->product->code === 'elp')
                                    Ecovida
                                @else
                                    {{ $retailerProduct->companyProduct->product->name }}
                                @endif
                            </li>

                            @if($retailerProduct->companyProduct->product->code === 'elp')

                            @elseif($retailerProduct->companyProduct->product->code === 'eb')
                                <li class="{{ request()->route()->getName() === 'cashiers::order.show' ? 'active' : '' }}">
                                    <a href="{{ route('cashiers::order.show', [
                                        'code'  => $retailerProduct->companyProduct->product->code,
                                        'rp_id' => encode($retailerProduct->id),
                                    ]) }}">
                                        <i class="glyphicon glyphicon-align-justify"></i>
                                        Ordenes de Cobro
                                    </a>
                                </li>
                                <li class="{{ request()->route()->getName() === 'cashiers::report' ? 'active' : '' }}">
                                    <a href="{{ route('cashiers::report', [
                                        'code'  => $retailerProduct->companyProduct->product->code,
                                        'rp_id' => encode($retailerProduct->id),
                                    ]) }}">
                                        <i class="glyphicon glyphicon-align-justify"></i>
                                        Reportes
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div id="content" class="col-lg-10 col-sm-10">
            <div>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">{{ $cashier->agency }}</a>
                    </li>
                </ul>
            </div>

            @yield('content')
        </div>
    </div>
    <hr>

    <footer class="row">
        <p class="col-md-9 col-sm-9 col-xs-12 copyright"><a href="#"></a></p>

        <p class="col-md-3 col-sm-3 col-xs-12 powered-by">
            Powered by: <a href="http://abrenet.com" target="_blank">Abrenet</a>
        </p>
    </footer>

</div>

@include('partials.popup')

{!! Html::script('js/app.js') !!}
</body>
</html>