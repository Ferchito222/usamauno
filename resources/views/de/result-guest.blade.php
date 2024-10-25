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
                <h4><i class="icon-arrow-left52 position-left"></i> <span
                            class="text-semibold">Seguro de Desgravamen</span></h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Desgravamen</a></li>
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
                            @include('partials.steps', [ 'product' => 'de', 'step' => 3, 'length' => 3 ])
                        </div>
                    </div>
                    <button style="float: right;" type="button" class="btn btn-rounded btn-default text-right"
                            title="Detalle de producto" data-placement="right" data-toggle="modal"
                            data-target="#modal_theme_primary">
                        <i class="icon-question7"></i> Producto
                    </button>
                </div>
                <div class="clearfix"></div>

                <div class="panel-body ">
                    <div class="col-xs-12 col-md-12">
                        <div class="col-md-6 col-md-offset-3 text-center" style="font-size: 120%;">
                            <div class="panel panel-body border-top-primary">
                                <h3>COTIZACIÓN Nº <span style="font-weight: bold;">{{ $header->quote_number }}</span></h3>
                                <h4>
                                    Monto Solicitado:
                                    <span style="font-weight: bold;">
                                        {{ number_format($header->amount_requested, 2) }} {{ $header->currency }}
                                    </span>
                                </h4>
                                <h4>
                                    Plazo del Crédito:
                                    <span style="font-weight: bold;">
                                        {{ $header->term }} {{ config('base.term_types.' . $header->type_term) }}
                                    </span>
                                </h4>
                                <div style="text-align: left;">
                                    Cliente (s):
                                    <br>
                                    <ul>
                                        @foreach($header->details as $detail)
                                            <li style="font-weight: bold;">{{ $detail->client->full_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="alert-info" style="font-size: 80%; padding: 10px;">
                                    Tome nota del presente número de cotización para informarle a su Oficial de Negocios
                                    quien continuará con su afiliación al presente seguro.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal_content_report')
@endsection
