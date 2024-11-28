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
                            @include('partials.steps', [ 'product' => 'de', 'step' => 2, 'length' => auth()->user()->username === 'guest' ? 3 : 5 ])
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

                @if(is_null($header))
                    <div class="alert bg-danger alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">La Cotización no existe</span>.
                    </div>
                @endif

                @if(session('success_question'))
                    <script>

                        $(function () {
                            messageAction('succes', "{{ session('success_question') }}");
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

                @if(session('error_question'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_question') }}");
                        });
                    </script>
                @endif

                @if(session('err_client'))
                    <div class="alert alert-warning alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{!! session('err_client') !!}</span>.
                    </div>
                @endif

                @if(session('responses'))
                    <div class="alert alert-warning alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">
                            {!! session('responses') !!}
                            <br>
                            Por favor vuelva a registrar las preguntas en el menú "Acción ->> Editar Cuestionario de Salud"
                        </span>
                    </div>
                @endif

                @if(! is_null($header))
                    {{-- @if ($user->profile->first()->slug !== 'GUP' && $header->details->count() < $coverage_detail)
                        <div class="col-xs-12">
                            <div class="col-md-8 col-md-offset-3">
                                {!! Form::open(['route' => ['client.search', 'rp_id' => $rp_id, 'header_id' => $header_id],
                                    'method' => 'get',
                                    'class'  => 'form-inline form-horizontal'
                                ]) !!}
                                <div class="form-group has-success">
                                    <label class="control-label col-lg-12 text-semibold" style="text-align: right;">
                                        Busqueda de datos:
                                    </label>
                                </div>
                                <!--
                                <div class="form-group has-success" style="margin-left: 0px;">
                                    <select name="s_document_type" class="form-control" style="box-sizing: content-box;">
                                        <option value="1">Carnet de Identidad</option>
                                        <option value="16">Carnet de Extranjero</option>
                                        <option value="0">Otros</option>
                                    </select>
                                </div>-->

                                <div class="form-group has-success" style="margin-left: 0px;">
                                    {!! Form::text('s_dni', old('s_dni'), [
                                        'class'        => 'form-control',
                                        'placeholder'  => 'Id de Cliente',
                                        'autocomplete' => 'off',
                                        'style'        => 'width: 200px;'
                                    ]) !!}
                                </div>
                                <!--
                                <div class="form-group has-success" style="margin-left: 0px;">
                                    {!! Form::text('s_complement', old('s_complement'), [
                                        'class'        => 'form-control',
                                        'placeholder'  => 'Complemento',
                                        'autocomplete' => 'off',
                                        'style'        => 'width: 110px;'
                                    ]) !!}
                                </div>-->

                                <button type="submit" class="btn btn-success" style="margin-left: 5px;">
                                    Buscar <i class="icon-search4"></i>
                                </button>
                                {!! Form::close() !!}
                            </div>
                            <div class="col-md-8 col-md-offset-3">
                                <div style="font-size: 11px; color: #9c1f1f;">
                                    No incluya prefijos ni caracteres alfanuméricos, digite únicamente números
                                    en el campo <strong>Id de Cliente</strong>.
                                </div>
                            </div>
                        </div>
                    @endif --}}
                    <div class="col-xs-12">
                        <br>                        
                        <div class="text-right">
                            @if ($header->details->count() < $coverage_detail)
                                @if($user->profile->first()->slug !== 'GUP')
                                    <a class="btn btn-primary"
                                       href="{{ route('de.detail.create', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                                       title="Nuevo Cliente">
                                        Añadir Cliente
                                        <i class="icon-plus2 position-right"></i>
                                    </a>
                                @endif
                            @endif

                            @if(($header->coverage->slug === 'IC' && $header->details->count() > 0) || ($header->coverage->slug !== 'IC' && $header->details->count() > 1))
                                @if($user->profile->first()->slug === 'GUP')
                                    <a class="btn btn-primary"
                                       href="{{ route('de.guest.result', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}">
                                        Enviar <i class="icon-arrow-right14 position-right"></i>
                                    </a>
                                @else
                                    <a class="btn btn-primary rounded5"
                                       href="{{ route('de.result', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}">
                                        Continuar <i class="icon-arrow-right14 position-right"></i>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="panel-body">
                        <div class="panel-body small">
                            <table class="table small" ng-controller="DetailDeController">
                                @if($header->details->count() > 0)
                                    <thead>
                                    <tr style="background: #f3f3f3 none repeat scroll 0 0; text-transform: uppercase;">
                                        <th>Cliente</th>
                                        <th>C.I.</th>
                                        <th>Nombres y Apellidos</th>
                                        <th>Fecha Nacimiento</th>
                                        <th>% Participación</th>
                                        <th>Cobertura de Desempleo</th>
                                        <th>Status</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($header->details as $key => $detail)
                                        <tr>
                                            <td style="background: #f3f3f3 none repeat scroll 0 0;">{{ $key === 0 ? 'T' : 'C' . $key }}</td>
                                            <td>
                                                <a href="#">
                                                    {{ $detail->client->dni
                                                        . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                                                    {{ $detail->client->extension }}
                                                </a>
                                            </td>
                                            <td>{{ $detail->client->full_name }}</td>
                                            <td>{{ dateToFormat($detail->client->birthdate) }}</td>
                                            <td>{{ $detail->percentage_credit }} %</td>
                                            <td>{{ $detail->cesantia?'SI':'NO' }}</td>
                                            <td><span class="label label-success">Completado</span></td>
                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li>
                                                                <a href="{{ route('de.detail.edit', [
                                                                    'rp_id'     => $rp_id,
                                                                    'header_id' => $header_id,
                                                                    'detail_id' => encode($detail->id)
                                                                    ]) }}">
                                                                    <i class="icon-plus2"></i> Editar datos</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route((! is_null($detail->response) ? 'de.question.edit' : 'de.question.create'), [
                                                                    'rp_id'     => $rp_id,
                                                                    'header_id' => $header_id,
                                                                    'detail_id' => encode($detail->id)
                                                                    ]) }}">
                                                                    <i class="icon-plus2"></i>Editar Cuestionario de Salud
                                                                </a>
                                                            </li>
                                                            @if($user->profile->first()->slug !== 'GUP')
                                                            
                                                            @if($header->coverage->slug == 'MC')
                                                                @if($key != 0 )
                                                                    <li>
                                                                        <a href="{{ route('de.detail.destroy', [
                                                                            'rp_id'     => $rp_id,
                                                                            'header_id' => $header_id,
                                                                            'detail_id' => encode($detail->id),
                                                                        ]) }}" ng-click="delete($event)">
                                                                            <i class="icon-plus2"></i>Eliminar Cliente</a>
                                                                    </li>
                                                                @endif
                                                            @else
                                                                <li>
                                                                    <a href="{{ route('de.detail.destroy', [
                                                                        'rp_id'     => $rp_id,
                                                                        'header_id' => $header_id,
                                                                        'detail_id' => encode($detail->id),
                                                                    ]) }}" ng-click="delete($event)">
                                                                        <i class="icon-plus2"></i>Eliminar Cliente</a>
                                                                </li>
                                                            @endif
                                                            @endif
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @endif
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
@endsection
