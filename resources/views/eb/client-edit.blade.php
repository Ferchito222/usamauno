@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i>
                    <span class="text-semibold">Seguro de Ecoaguinaldo</span>
                </h4>
                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Ecoaguinaldo</a></li>
                    <li class="active">Cotizar</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row panel">
        <div class="col-md-12">
            <!-- Horizontal form -->
            <div class="border-top-primary" ng-controller="HeaderEbController">
                <div class="panel-heading divhr">
                    <div class="steps-basic2 wizard">
                        <div class="steps">
                            <ul>
                                <li class="first done">
                                    <a href="#">
                                        <span class="number">1</span> Datos del Cliente
                                    </a>
                                </li>
                                <li class="first done">
                                    <a href="#">
                                        <span class="number">2</span> Resultado Cotización
                                    </a>
                                </li>
                                <li class="current">
                                    <a href="#">
                                        <span class="current-info audible">current step: </span>
                                        <span class="number">3</span> Emisión de la Póliza de Ecoaguinaldo
                                    </a>
                                </li>
                                <li class="disabled last">
                                    <a href="#">
                                        <span class="number">4</span> Impresión de la Póliza
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button style="float: right;" type="button" class="btn btn-rounded btn-default text-right"
                            title="Detalle de producto" data-placement="right" data-toggle="modal"
                            data-target="#modal_theme_primary">
                        <i class="icon-question7"></i> Producto
                    </button>
                </div>

                @if(session('error_header'))
                    <div class="alert alert-danger alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{{ session('error_header') }}</span>.
                    </div>
                @endif

                @if(session('error_client'))
                    <script>
                        $(function () {
                            messageAction('info', "{{ session('error_client') }}");
                        });
                    </script>
                @endif

                <div class="col-xs-12">
                    {!! Form::open(['route' => ['eb.client.update', 'rp_id' => $rp_id, 'header_id' => $header_id],
                        'method'    => 'put',
                        'class'     => 'form-horizontal',
                        'id'        => 'form-init',
                        'ng-submit' => 'sendForm($event)',
                    ]) !!}

                    @include('eb.partials.inputs-quote', [ 'issuance' => true ])

                    {!! Form::close() !!}
                </div>
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
@endsection