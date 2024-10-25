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
            <div>
                <div class="panel-heading divhr">
                    <div class="steps-basic2 wizard">
                        <div class="steps">
                            <ul>
                                <li class="current">
                                    <a href="#">
                                        <span class="current-info audible">current step: </span>
                                        <span class="number">1</span> Datos del Cliente
                                    </a>
                                </li>
                                <li class="disabled last">
                                    <a href="#">
                                        <span class="number">2</span> Resultado Cotización
                                    </a>
                                </li>
                                <li class="disabled last">
                                    <a href="#">
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
                <div class="clearfix"></div>

                @if(session('success_header'))
                    <script>
                        $(function () {
                            messageAction('succes', "{{ session('success_header') }}");
                        });
                    </script>
                @endif

                @if(session('error_header'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_header') }}");
                        });
                    </script>
                @endif

                @if(session('error_client'))
                    <script>
                        $(function () {
                            messageAction('info', "{{ session('error_client') }}");
                        });
                    </script>
                @endif

                @if(session('error_client_edit'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_client_edit') }}");
                        });
                    </script>
                @endif

                @if(session('success_client'))
                    <script>
                        $(function () {
                            messageAction('succes', "{{ session('success_client') }}");
                        });
                    </script>
                @endif

                @if(! is_null($header))
                    <table class="table datatable-basic2">
                        <thead>
                        <tr>
                            <th>Cliente Ecofuturo</th>
                            <th>C.I.</th>
                            <th>Nombres y Apellidos</th>
                            <th>Fecha Nacimiento</th>
                            <th>Departamento</th>
                            <th class="text-center">Accion</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $header->detail->client_eco ? 'SI' : 'NO' }}</td>
                            <td>
                                <a href="#">{{ $header->detail->client->dni }} {{ $header->detail->client->extension }}</a>
                            </td>
                            <td>{{ $header->detail->client->full_name }}</td>
                            <td>{{ dateToFormat($header->detail->client->birthdate) }}</td>
                            <td>{{ $header->detail->client->birth_place }}</td>
                            <td class="text-center">
                                <ul class="icons-list">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="{{ route('eb.edit', [
                                                            'rp_id'     => $rp_id,
                                                            'header_id' => $header_id,
                                                        ]) }}"><i class="icon-plus2"></i> Editar datos</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        </tbody>
                        @endif
                    </table>
                    <div class="col-xs-12">
                        <div class="text-right">
                            <a class="btn btn-primary"
                               href="{{ route('eb.result', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}">
                                Continuar <i class="icon-arrow-right14 position-right"></i>
                            </a>
                        </div>
                        <br>
                    </div>
            </div>
        </div>
    </div>
@endsection
