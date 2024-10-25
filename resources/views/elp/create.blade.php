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
                    <span class="text-semibold">Seguro de Ecovida / Ecoprotección</span>
                </h4>
                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Ecovida / Ecoprotección</a></li>
                    <li class="active">Cotizar</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row panel">
        <div class="col-md-12">
            <!-- Horizontal form -->
            <div class="border-top-primary" ng-controller="HeaderElpController">
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
                                        <span class="number">3</span> Emisión de la Póliza de Ecovida / Ecoprotección
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

                @if(session('error_header'))
                    <div class="alert alert-danger alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{{ session('error_header') }}</span>.
                    </div>
                @endif

                @if(session('error_client'))
                    <script>
                        $(function () {
                            messageAction('info', "{{ session('error_client') }}");
                        });
                    </script>
                @endif

                <div class="col-xs-12">
                    {!! Form::open(['route' => ['client.search', 'rp_id' => $rp_id],
                        'method' => 'get',
                        'class'  => 'form-inline form-horizontal',
                    ]) !!}
                    <div class="form-group col-md-12 col-md-offset-2">
                        <label class="control-label col-lg-6 text-semibold" style="text-align: right;">
                            Es Cliente de Ecofuturo?
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('is_client', 1, (old('is_client') == 1 ? true : false), ['class' => 'styled', 'id' => 'is_client_1']) !!}
                            SI
                        </label>
                        <label class="radio-inline">
                            {!! Form::radio('is_client', 0, (old('is_client') == 0 ? true : false), ['class' => 'styled', 'id' => 'is_client_0']) !!}
                            NO
                        </label>
                        <label id="location-error" class="validation-error-label"
                               for="location">{{ $errors->first('client') }}</label>
                    </div>

                    <div class="form-group col-md-10 col-md-offset-2" id="form-search" style="display: none;">
                        <label class="control-label col-lg-5 text-semibold" style="text-align: right;">
                            Busqueda de datos:
                        </label>
                        <div class="form-group has-success">
                            {!! Form::text('s_dni', old('s_dni'), [
                                'class'        => 'form-control',
                                'placeholder'  => 'Documento de identidad',
                                'autocomplete' => 'off',
                                'style'        => 'width: 180px;'
                            ]) !!}
                        </div>
                        <div class="form-group has-success">
                            <label>Extensión</label>
                            {!! SelectField::input('s_extension', $data['cities']['CI']->toArray(), [
                                'class' => 'form-control',
                                'style' => 'width: 150px; font-size: 90%;'
                                ], old('s_extension'))
                            !!}
                        </div>
                        <button type="submit" class="btn btn-success" style="margin-left: 5px;">
                            Buscar <i class="icon-search4"></i>
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="col-xs-12">
                    {!! Form::open(['route' => ['elp.store', 'rp_id' => $rp_id],
                        'method'        => 'post',
                        'class'         => 'form-horizontal',
                        'id'            => 'form-init',
                        'data-product'  => 'elp',
                        'ng-submit'     => 'sendForm($event)',
                    ]) !!}

                    @include('elp.partials.inputs-quote')

                    {!! Form::close() !!}
                </div>
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
@endsection