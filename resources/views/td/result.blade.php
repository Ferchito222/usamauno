<!--edw-->
@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    @include('partials.fields', ['type' => 'menu-header', 'product' => 'td'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                @include('partials.step', ['step' => '3', 'product' => 'td'])
                @include('partials.messages')

                <div class="panel-body ">
                    <div class="col-xs-12 col-md-12">

                        <div class="col-md-4 ">
                            <div class="panel panel-body border-top-primary text-center">
                                <div class="form-group">
                                    {!! Html::image($retailerProduct->companyProduct->company->image, null, ['style' => 'width: 150px;']) !!}
                                </div>
                                <h6 class="no-margin text-semibold">Prima Anual:</h6>
                                <p class="text-muted content-group-sm">
                                    {{ $header->currency }} {{ number_format($header->total_premium, 3) }}
                                </p>
                                <h6 class="no-margin text-semibold">Prima Mensual</h6>
                                <p class="text-muted content-group-sm">
                                    {{ $header->currency }} {{ number_format(($header->total_premium_month), 3) }}
                                </p>
                                <a href="{{ route('certificate.show', [
                                    'code'      => 'td',
                                    'rp_id'     => $rp_id,
                                    'type'      => 'slip',
                                    'header_id' => encode($header->id),
                                    'format'    => 'pdf',
                                ]) }}" target="_blank" class="btn btn-success">
                                    Ver Cotización
                                </a>
                                <hr>
                                <a href="{{ route('td.edit', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                                   class="btn btn-primary"><i class="icon-arrow-right14 position-left"></i>
                                    Emitir
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.modal_content_report')
@endsection
