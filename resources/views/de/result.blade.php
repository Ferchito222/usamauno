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
            <!-- Horizontal form -->
            <div class="panel panel-flat border-top-primary">
                <div class="panel-heading divhr">
                    <div class="steps-basic2 wizard">
                        <div class="steps">
                            @include('partials.steps', [ 'product' => 'de', 'step' => 3, 'length' => auth()->user()->username === 'guest' ? 3 : 5 ])
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
                    <div class="alert alert-warning alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{{ session('error_header') }}</span>.
                    </div>
                @endif

                <div class="panel-body ">
                    <div class="col-xs-12 col-md-12">
                        @if($retailerProduct->companyProduct->product->code == 'de' && ($retailerProduct->type == 'MP' || $retailerProduct->type == 'RP'))
                            <div class="col-md-4">
                                <div class="panel panel-body text-center rounded-top10 rounded-bottom10">
                                    <div class="form-group">
                                        {!! Html::image($retailerProduct->companyProduct->company->image, null, ['style' => 'width: 250px;']) !!}
                                    </div>

                                    @if(! in_array($header->creditProduct->slug, [ 'PMO', 'PLT' ]))
                                        <h6 class="no-margin text-semibold">Tasa del prestamo:</h6>
                                        <p class="text-muted content-group-sm">
                                            {{ $header->total_rate }} % Mensual ó
                                            {{ number_format(($header->total_rate*12), 3, '.', '')}} % Anual
                                            <br>
                                        </p>
                                    @endif

                                    @if($header->details->where('cesantia', 1)->count() > 0)
                                        <h6 class="no-margin text-semibold">Tasa de Desempleo:</h6>
                                        <p class="text-muted content-group-sm">
                                            {{ $header->details->where('cesantia', 1)->first()->rate_cesantia }} % Mensual
                                        </p>
                                    @endif
                                        
                                    <hr>
                                    <a href="{{ route('de.edit', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                                       class="btn btn-primary rounded5"><i class="icon-arrow-right14 position-left"></i>
                                        Emitir
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- /horizotal form -->
            </div>
        </div>
    </div>

    <!-- modal -->
    @include('partials.modal_content_report')
    <!-- /modal -->

@endsection
