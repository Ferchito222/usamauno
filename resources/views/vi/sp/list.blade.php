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
                    <h6 class="form-wizard-title2 text-semibold">
                        <span class="col-md-11">
                            <span class="form-wizard-count">6</span>
                            Lista de clientes
                            <small class="display-block">Lista de clientes</small>
                        </span>
                        <span class="col-md-1">
                            <button style="float: left;" type="button" class="btn btn-rounded btn-default text-right"
                                    data-popup="tooltip" title="Detalle de producto" data-placement="right"
                                    data-toggle="modal" data-target="#modal_theme_primary">
                                <i class="icon-question7"></i> Producto
                            </button>
                        </span>
                    </h6>
                </div>

                @if(session('error_cache'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_cache') }}");
                        });
                    </script>
                @endif

                {!! Form::open(['route' => ['de.vi.sp.list.store', 'rp_id' => $rp_id, 'header_id' => $header_id, 'sp_id' => $sp_id],
                    'method'        => 'post',
                    'class'         => '',
                    'ng-controller' => 'HeaderViController',
                    'ng-submit'     => 'sendForm($event)',
                ]) !!}
                    {!! Form::hidden('rp_id', encrypt($rp_id)) !!}
                    {!! Form::hidden('header_id', $header_id) !!}
                    {!! Form::hidden('sp_id', $sp_id) !!}
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <th>Titular</th>
                            <th>C.I.</th>
                            <th>Nombres y Apellidos</th>
                            <th>Fecha Nacimiento</th>
                            <th>Departamento</th>
                            <th>% Credito</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($header->details as $key => $detail)
                            <tr>
                                <td>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="styled" name="clients[{{ $key + 1 }}]"
                                                   value="{{ encode($detail->id) }}">
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="#">{{ $detail->client->dni }} {{ $detail->client->extension }}</a></td>
                                <td>{{ $detail->client->full_name }}</td>
                                <td>{{ dateToFormat($detail->client->birthdate) }}</td>
                                <td>{{ $detail->client->birth_place }}</td>
                                <td>{{ $detail->percentage_credit }} %</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        <a href="{{ route('de.issuance', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                           class="btn border-slate text-slate-800 btn-flat">Cancelar</a>

                        {!! Form::button('Continuar <i class="icon-arrow-right14 position-right"></i>', [
                            'type' => 'submit',
                            'class' => 'btn btn-primary'])
                        !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection