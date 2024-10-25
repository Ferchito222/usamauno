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
                    <span class="text-semibold">{{ $title }}</span>
                </h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li class="active">Reportes</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                <div class="panel-body">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="active">
                                <a href="#">
                                    Reporte Grafico
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {!! Form::open(['url' => request()->fullUrl(),
                                'method' => 'get',
                                'class'  => 'form-horizontal'
                            ]) !!}
                            <div class="col-xs-12 col-md-12 display-block">
                                @include('report.graphic.partials.inputs-search-graphic')
                            </div>
                            {!! Form::close() !!}

                            <div class="clearfix"></div>
                            <hr>

                            @if(count($headers) > 0)
                                @include('report.graphic.partials.graphic')
                                <br>
                                <br>
                                <a href="{{ route('graphic.report', [
                                        'format' => 'pdf',
                                        request()->getQueryString(),
                                    ]) }}" class="btn btn-success" target="_blank">
                                        Exportar a PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection