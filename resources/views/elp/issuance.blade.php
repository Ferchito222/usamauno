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
                    <span class="text-semibold">Seguro de Ecovida / Ecoprotección</span>
                </h4>
                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Ecovida / Ecoprotección</a></li>
                    <li class="active">Cotizar</li>
                </ul>
            </div>

        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
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
                                <li class="first done">
                                    <a href="#">
                                        <span class="number">3</span> Emisión de la Póliza de Ecovida / Ecoprotección
                                    </a>
                                </li>
                                <li class="current">
                                    <a href="#">
                                        <span class="current-info audible">current step: </span>
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
                <div class="clearfix"></div>
                @if(session('error_header'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_header') }}");
                        });
                    </script>
                @endif

                @if(session('success_header'))
                    <script>
                        $(function () {
                            messageAction('succes', "{{ session('success_header') }}");
                        });
                    </script>
                @endif

                <div class="panel-body" ng-controller="HeaderElpController">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="modal-header bg-primary">
                            <h6 class="modal-title">Póliza {{ $header->prefix }} - {{ $header->issue_number }}</h6>
                        </div>
                        <div class="panel panel-body border-top-primary text-center">
                            <p class="text-muted content-group-sm">Cotizacion/Emisión </p>
                            <div class="col-md-12">
                                <p>
                                    <a href="{{ route('certificate.show', [
                                        'code'      => 'elp',
                                        'rp_id'     => $rp_id,
                                        'type'      => 'all',
                                        'header_id' => $header_id,
                                        'format'    => 'print',
                                    ]) }}"
                                       class="btn btn-primary btn-labeled btn-xlg col-lg-12"
                                       ng-click="openCertificate($event)">
                                        <b><i class="icon-printer4"
                                              ng-click="$event.stopPropagation(); $event.preventDefault()"></i></b>
                                        Imprimir Todo
                                    </a>
                                </p>
                                <div class="col-md-12">&nbsp;</div>
                                <p>
                                    <a href="{{ route('certificate.show', [
                                        'code'      => 'elp',
                                        'rp_id'     => $rp_id,
                                        'type'      => 'slip',
                                        'header_id' => $header_id,
                                        'format'    => 'print',
                                    ]) }}"
                                       class="btn btn-info btn-labeled btn-xlg col-lg-12"
                                       ng-click="openCertificate($event)">
                                        <b><i class="icon-printer4"
                                              ng-click="$event.stopPropagation(); $event.preventDefault()"></i></b>
                                        Ver Slip de Cotización
                                    </a>
                                </p>
                                <div class="col-md-6">&nbsp;</div>
                                <p>
                                    <a href="{{ route('certificate.show', [
                                        'code'      => 'elp',
                                        'rp_id'     => $rp_id,
                                        'type'      => 'certificate',
                                        'header_id' => $header_id,
                                        'format'    => 'print',
                                    ]) }}"
                                       class="btn btn-info btn-labeled btn-xlg col-lg-12"
                                       ng-click="openCertificate($event)">
                                        <b><i class="icon-printer4"
                                              ng-click="$event.stopPropagation(); $event.preventDefault()"></i></b>
                                        Ver Certificado de Emisión
                                    </a>
                                </p>
                                <div class="col-md-6">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--@include('partials.modal_content_report')--}}
@endsection