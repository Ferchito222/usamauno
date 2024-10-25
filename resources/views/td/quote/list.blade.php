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
                            class="text-semibold">Emisión de Solicitudes</span></h4>
            </div>
        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal form -->
            <div class="panel panel-flat border-top-primary">
                <div class="clearfix"></div>

                <div class="panel-body">
                    <div class="col-xs-12">
                        {!! Form::open(['route' => ['td.issue.lists', 'rp_id' => $rp_id], 'method' => 'get', 'class' => 'form-horizontal']) !!}
                        <div class="col-xs-12 col-md-12">
                            @include('report.partials.inputs-search', ['type' => 'Q'])
                        </div>
                        {!! Form::close() !!}

                        <table class="table datatable-fixed-left table-striped small_body" width="100%">
                            <thead>
                            <tr>
                                <th>Nro. de Cotización</th>
                                <th>Cliente</th>
                                <th>C.I.</th>
                                <th>Materia</th>
                                <th>Descripción</th>
                                <th>Uso</th>
                                <th>Departamento</th>
                                <th>Zona</th>
                                <th>Ciudad/Localidad</th>
                                <th>Dirección</th>
                                <th>Valor Asegurado</th>
                                <th>Usuario</th>
                                <th>Sucursal / Agencia</th>
                                <th>Fecha de Ingreso</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($headers as $header)
                                @foreach ($header->details as $detail)
                                    <tr>
                                        <td>{{ $header->quote_number }}</td>
                                        <td>{{ $header->client->full_name }}</td>
                                        <td>{{ $header->client->dni }} {{ $header->client->extension }}</td>
                                        <th>{{ $detail->matter_insured_text }}</th>
                                        <th>{{ $detail->matter_description }}</th>
                                        <th>{{ $detail->use_text }}</th>
                                        <th>{{ $detail->city }}</th>
                                        <th>{{ $detail->zone }}</th>
                                        <th>{{ $detail->locality }}</th>
                                        <th>{{ $detail->address }}</th>
                                        <td>{{ number_format($detail->insured_value, '2') }} {{ $header->currency }}</td>
                                        <td>{{ $header->user->full_name }}</td>
                                        <td>
                                            {{ ! is_null($header->user->city) ? $header->user->city->name : '' }}
                                            {{ ! is_null($header->user->agency) ? '/ ' . $header->user->agency->name : '' }}
                                        </td>
                                        <td>
                                            {{ $header->created_date }}
                                        </td>
                                        <td>
                                            <ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            @if ($header->days_from_creation > $parameter->expiration)
                                                                <a href="#">
                                                                    <span class="label label-danger">Fecha limite de emisión caducada</span>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('td.result', ['rp_id' => $rp_id, 'header_id' => encode($header->id)]) }}">
                                                                    <i class="icon-database-edit2"></i> Emitir Solicitud
                                                                </a>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('certificate.show', [
                                                                    'code'      => 'td',
                                                                    'rp_id'     => $rp_id,
                                                                    'type'      => 'slip',
                                                                    'header_id' => encode($header->id),
                                                                    'format'    => 'pdf',
                                                                ]) }}" target="_blank">
                                                                <i class="icon-plus2"></i> Ver Slip de Cotización

                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /horizotal form -->
        </div>
    </div>

    <!-- modal -->
    @include('partials.modal_content_report')
    <!-- /modal -->

@endsection