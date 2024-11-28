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
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Seguro de Desgravamen</span></h4>

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
                            <span class="form-wizard-count">2</span>
                            {{ $client->full_name }}
                            <small class="display-block"></small>
                        </span>
                        <span class="col-md-1">
                            <button style="float: left;" type="button" class="btn btn-rounded btn-default text-right" data-popup="tooltip" title="Detalle de producto" data-placement="right" data-toggle="modal" data-target="#modal_theme_primary">
                                <i class="icon-question7"></i> Producto
                            </button>
                        </span>
                    </h6>
                </div>

                <div class="steps-basic2 wizard">
                    <div class="steps">
                        @include('partials.steps', [ 'product' => 'de', 'step' => 2, 'length' => auth()->user()->username === 'guest' ? 3 : 5 ])
                    </div>
                </div>

                @if(session('error_client'))
                    <script>
                        $(function () {
                            messageAction('info', "{{ session('error_client') }}");
                        });
                    </script>
                @endif

                @if(session('error_client_edit'))
                    <div class="alert bg-danger alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
                        <span class="text-semibold">{{ session('error_client_edit') }}</span>.
                    </div>
                @endif

                {!! Form::open(['route' => ['de.detail.update',  'rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => $detail_id], 
                'method' => 'put', 
                'ng-controller' => 'ClientController',
                'class' => 'form-horizontal']) !!}
                    <div class="panel-body ">
                        {{-- @if($header->rejetedCustomers->count()>0)
                            @include('client.de.link-rejected-customer')
                        @endif --}}
                        @include('client.de.partials.inputs-quote')

                        <div class="text-right">
                            <a href="{{ route('de.client.list', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                               class="btn border-slate text-slate-800 btn-flat rounded5">Cancelar</a>

                            {!! Form::button('Actualizar Cliente <i class="icon-arrow-right14 position-right"></i>', [
                                'type' => 'submit',
                                'class' => 'btn btn-primary rounded5'])
                            !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>

            <!-- /horizotal form -->
        </div>
    </div>
@endsection