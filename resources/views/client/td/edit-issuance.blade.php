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
                            class="text-semibold">Seguro de Automotores</span></h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Automotores</a></li>
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
                <div class="steps-basic2 wizard">
                    <div class="steps">
                        <ul>
                            <li class="first done">
                                <a href="#">
                                    <span class="number">1</span> Datos del Prestamo y Cliente
                                </a>
                            </li>
                            <li class="first done">
                                <a href="#">
                                    <span class="number">2</span> Datos del Vehículo
                                </a>
                            </li>
                            <li class="first done">
                                <a href="#">
                                    <span class="number">3</span> Resultado Cotización
                                </a>
                            </li>
                            <li class="current">
                                <a href="#">
                                    <span class="number">4</span> Datos del Cliente
                                </a>
                            </li>
                            <li class="disabled last">
                                <a href="#">
                                    <span class="number">5</span> Impresión de la Póliza
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                @include('partials.messages')
                {!! Form::open(['route' => ['td.client.i.update',
                    'rp_id'     => $rp_id,
                    'header_id' => $header_id,
                    'client_id' => $client_id,
                    isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
                    ], 'method' => 'put', 'class' => 'form-horizontal'
                ]) !!}

                <div class="panel-body ">
                    @include('client.td.partials.inputs-quote', ['form' => 'edit'])

                    <div class="text-right">
                        @if(request()->has('coverage'))
                            <input type="hidden" name="coverage" value="{{ request()->get('coverage') }}">

                            <a href="{{ route('td.coverage.edit', [
                                'rp_id'     => $rp_id,
                                'de_id'     => request()->get('coverage'),
                                'header_id' => $header_id,
                            ]) }}" class="btn border-slate text-slate-800 btn-flat">Cancelar</a>
                        @else
                            <a href="{{ route('td.edit', [
                                'rp_id'     => $rp_id,
                                'header_id' => $header_id,
                                isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
                            ]) }}" class="btn border-slate text-slate-800 btn-flat">Cancelar</a>
                        @endif

                        {!! Form::button('Actualizar Cliente <i class="icon-arrow-right14 position-right"></i>', [
                            'type' => 'submit',
                            'class' => 'btn btn-primary'])
                        !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

            <!-- /horizotal form -->
        </div>
    </div>
@endsection