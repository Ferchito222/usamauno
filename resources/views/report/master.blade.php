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
                        @include('report.partials.master-tabs')

                        <div class="tab-content">
                            {!! Form::open(['url' => request()->fullUrl(),
                                'method' => 'get',
                                'class'  => 'form-horizontal'
                            ]) !!}
                            <div class="col-xs-12 col-md-12 display-block">
                                @include('report.partials.inputs-search')
                            </div>
                            {!! Form::close() !!}

                            <div class="clearfix"></div>
                            <hr>

                            @if((isset($headers) && $headers->count() > 0) || (isset($credit_lines) && $credit_lines->count() > 0))
                                @if($type === 'OP')
                                    @include('report.rows.rows-cl')
                                @else
                                    @include('report.rows.rows-' . $code)
                                @endif
                                <br>

                                @if(isset($headers))
                                    {!! $headers->appends(request()->all())->render() !!}
                                @elseif(isset($credit_lines))
                                    {!! $credit_lines->appends(request()->all())->render() !!}
                                @endif

                                <br>

                                <a href="{{ route('report.master', [
                                    'code'   => $code,
                                    'rp_id'  => $rp_id,
                                    'type'   => request()->route()->getParameter('type'),
                                    'format' => 'xls',
                                    request()->getQueryString(),
                                ]) }}" class="btn btn-success">
                                    Exportar a Excel
                                </a>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
