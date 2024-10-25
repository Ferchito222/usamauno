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
                <h4 class="label label-primary"><i class="icon-arrow-right5 position-left"></i>
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
    {!! Form::open(['route' => ['dp.policy.update', 'code' => $code, 'rp' => $rp, 'id' => encode($header->id)],
        'method'        => 'put',
        'class'         => 'form-horizontal',
        'ng-controller' => 'ReportController',
    ]) !!}

    <div class="panel-body">
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading text-center">
                    <div class="checkbox" style="display:inline">
                        <label style="font-size: 120%;">
                            <input type="checkbox" name="e-policy" style="float:inherit; cursor: pointer;"
                                   ng-model="e_policy"/>Póliza
                        </label>
                    </div>
                </div>
            </div>

            @include('data-policy.policy')

        <div class="panel panel-bordered panel-info">
            <div class="panel-heading text-center">
                <div class="checkbox" style="display:inline">
                    <label style="font-size: 120%;">
                        <input type="checkbox" name="e-client" style="float:inherit; cursor: pointer;"
                               ng-model="e_client"/>Cliente (s)
                    </label>
                </div>
            </div>
        </div>

        @include('data-policy.client')

        @if($code == 'au')
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading text-center">
                    <div class="checkbox" style="display:inline">
                        <label style="font-size: 120%;">
                            <input type="checkbox" name="e-vehicle" style="float:inherit; cursor: pointer;"
                                   ng-model="e_vehicle"/>Vehículos (s)
                        </label>
                    </div>
                </div>
            </div>

            @include('data-policy.vehicle')
        @endif

        @if($code === 'td')
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading text-center">
                    <div class="checkbox" style="display:inline">
                        <label style="font-size: 120%;">
                            <input type="checkbox" name="e-risk" style="float:inherit; cursor: pointer;"
                                   ng-model="e_risk"/>Riesgos (s)
                        </label>
                    </div>
                </div>
            </div>

            @include('data-policy.risk')
        @endif
        @if($code === 'ema')
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading text-center">
                    <div class="checkbox" style="display:inline">
                        <label style="font-size: 120%;">
                            <input type="checkbox" name="e-ema" style="float:inherit; cursor: pointer;"
                                   ng-model="e_ema"/>Riesgos (s)
                        </label>
                    </div>
                </div>
            </div>

            @include('data-policy.ema')
        @endif
        @if($code === 'rma')
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading text-center">
                    <div class="checkbox" style="display:inline">
                        <label style="font-size: 120%;">
                            <input type="checkbox" name="e-rma" style="float:inherit; cursor: pointer;"
                                   ng-model="e_rma"/>Riesgos (s)
                        </label>
                    </div>
                </div>
            </div>

            @include('data-policy.rma')
        @endif
        
        @if($code == 'none')
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading text-center">
                    <div class="checkbox" style="display:inline">
                        <label style="font-size: 120%;">
                            <input type="checkbox" name="e-beneficiary" style="float:inherit; cursor: pointer;"
                                   ng-model="e_beneficiary"/>Beneficiario
                            (s)
                        </label>
                    </div>
                </div>
            </div>

            @include('data-policy.beneficiaries')
        @endif
        

        <div class="text-center">
            <button type="submit" class="btn btn-success">Guardar cambios
                <i class="glyphicon glyphicon-floppy-disk position-right"></i>
            </button>

            <a href="{{ route('dp.search') }}" class="btn btn-default">Cancelar
                <i class="glyphicon glyphicon-remove-circle position-right"></i>
            </a>
        </div>
    </div>

    {!! Form::close() !!}

    @include('partials.modal_content_report')
@endsection