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
            <div class="page-title" align="center">
                <h4 class="label {{ $log->level === 200 ? 'label-success' : 'label-warning' }}">
                    <i class="icon-arrow-right5 position-left"></i>
                    <span class="text-semibold text-size-large">
                        Póliza de {{ $retailerProduct->companyProduct->product->name }}
                        <span style="font-size: 150%;">{{ $header->prefix }} - {{ $header->issue_number }}</span>
                    </span>
                </h4>
                <h6 class="text-size-small">Fecha de Emisión: {{ date_format_report($header->date_issue) }}</h6>
                <h6 class="text-size-small">Versión de Certificado: v-{{ $header->certificate->version }}</h6>
                <br>
                @if($header->creditProduct != null && $header->creditProduct->slug === 'PMO')
                    <a href="{{ route('certificate.show', [
                        'code'      => 'de',
                        'rp_id'     => encode($header->ad_retailer_product_id),
                        'type'      => 'certificate',
                        'header_id' => encode($header->id),
                        'format'    => 'pdf',
                    ]) }}"
                       class="btn btn-default" style="font-size: 100%;" target="_blank">
                        Ver Certificado de Emisión
                    </a>
                @elseif($header->issued == true)
                    
                    <a href="{{ route('certificate.show', [
                            'code'      => $code,
                            'rp_id'     =>encode($header->ad_retailer_product_id),
                            'type'      => 'certificate',
                            'header_id' => encode($header->id),
                            'format'    => 'pdf',
                        ]) }}" class="btn btn-default" style="font-size: 100%;" target="_blank">
                        Ver Certificado
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="panel-body ">
        <p class="alert-warning">
            {{ var_dump($log->data) }}
        </p>

        <div class="text-center">
            <a href="{{ route('dp.search') }}" class="btn btn-info">Volver al panel
                <i class="glyphicon glyphicon-th-large position-right"></i>
            </a>
        </div>
    </div>

    @include('partials.modal_content_report')
@endsection