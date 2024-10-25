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
                            class="text-semibold">Anulación de Pólizas</span></h4>
            </div>
        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                <div class="clearfix"></div>

                <div class="panel-body">
                    <div class="col-xs-12">
                        {!! Form::open(['route' => ['eb.cancel.lists', 'rp_id' => $rp_id],
                            'method' => 'get',
                            'class'  => 'form-horizontal'
                        ]) !!}
                        <div class="col-xs-12 col-md-12">
                            @include('report.partials.inputs-search')
                        </div>
                        {!! Form::close() !!}

                        <table class="table datatable-fixed-left table-striped" width="100%"
                               ng-controller="CancellationController">
                            <thead>
                            <tr>
                                <th>Nro. de Póliza</th>
                                <th>Cliente de Ecofuturo</th>
                                <th>Cliente</th>
                                <th>C.I.</th>
                                <th>Prima</th>
                                <th>Usuario</th>
                                <th>Sucursal / Agencia</th>
                                <th>Fecha de Ingreso</th>
                                {{--<th>Certificados Anulados</th>--}}
                                <th>Emitido</th>
                                <th>Fecha de Emisión</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($headers as $header)
                                <tr>
                                    <td>{{ $header->prefix }}-{{ $header->issue_number }}</td>
                                    <td>{{ $header->detail->client_eco ? 'SI' : 'NO' }}</td>
                                    <td>{{ $header->detail->client->full_name }}</td>
                                    <td>{{ $header->detail->client->dni }} {{ $header->detail->client->extension }}</td>
                                    <td>{{ number_format($header->detail->premium, '2', '.', ',') }}</td>
                                    <td>{{ $header->user->full_name }}</td>
                                    <td>
                                        {{ ! is_null($header->user->city) ? $header->user->city->name : '' }}
                                        {{ ! is_null($header->user->agency) ? '/ ' . $header->user->agency->name : '' }}
                                    </td>
                                    <td>
                                        {{ date_format_report($header->created_at) }}
                                    </td>
                                    <td>{{ $header->issued ? 'SI' : 'NO' }}</td>
                                    <td>{{ date_format_report($header->date_issue) }}</td>
                                    <td>
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a href="{{ route('eb.cancel.create', ['rp_id' => $rp_id, 'header_id' => encode($header->id)]) }}"
                                                           ng-click="cancelCreate($event)">
                                                            <i class="icon-cancel-circle2"></i> Anular
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.modal_content_report')
@endsection