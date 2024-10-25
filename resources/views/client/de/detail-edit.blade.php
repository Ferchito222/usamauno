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
                            <span class="form-wizard-count">4</span>
                            Emisión de Póliza de Desgravamen
                            <small class="display-block">Emisión de Póliza</small>
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
                        @include('partials.steps', [ 'product' => 'de', 'step' => 4, 'length' => auth()->user()->username === 'guest' ? 3 : 5 ])
                    </div>
                </div>

                @if(session('error_client'))
                    <script>
                        $(function () {
                            messageAction('info', "{{ session('error_client') }}");
                        });
                    </script>
                @endif

                <div class="panel-body ">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="modal-header bg-primary">
                            <div class="panel-heading">
                                <h6 class="modal-title">Información del Cliente <strong>{{ $detail->client->full_name }}</strong></h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success">
                            <div class="panel-heading">
                                <!--<h5 class="panel-title">Infomración del TITULAR</h5>-->
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                        <li><a data-action="close"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <!--<hr>-->
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Documento de identidad: </strong>
                                    </div>
                                    <div class="col-lg-7">
                                        {{ $detail->client->dni
                                            . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                                        {{ $detail->client->extension }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Tipo de documento: </strong>
                                    </div>
                                    <div class="col-lg-7">
                                        @foreach($data['document_type'] as $document_type)
                                            @if($document_type['id'] == $detail->client->document_type)
                                                {{ $document_type['name'] }}
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Fecha de nacimiento: </strong>
                                    </div>
                                    <div class="col-lg-7">
                                        {{ dateToFormat($detail->client->birthdate) }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Género: </strong>
                                    </div>
                                    <div class="col-lg-7">
                                        @foreach($data['gender'] as $gender)
                                            @if($gender['id'] == $detail->client->gender)
                                                {{ $gender['name'] }}
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Peso: </strong>
                                    </div>
                                    <div class="col-lg-7">
                                        {{ $detail->client->weight }} kg.
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Estatura: </strong>
                                    </div>
                                    <div class="col-lg-7">
                                        {{ $detail->client->height }} (cm).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 col-md-offset-1">
                        <div class="modal-header bg-primary">
                            <div class="panel-heading">
                                <h6 class="modal-title">Cuestionario de Salud</h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success">
                            <div class="panel-heading">
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li>
                                        <li><a data-action="close"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                @foreach(json_decode($detail->response->response) as $key => $response)
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <div class="col-lg-10">
                                            <strong>{{ $key }}. {{ $response->question }}</strong>
                                        </div>
                                        <div class="col-lg-2">
                                            {{ $response->response == 1 ? 'SI' : 'NO' }}
                                        </div>
                                    </div>
                                @endforeach
                                <div class="clearfix">&nbsp;</div>
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        {{ $detail->response->observation }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {!! Form::open(['route' => ['de.detail.i.update', 
                    'rp_id'     => $rp_id, 
                    'header_id' => $header_id, 
                    'detail_id' => encode($detail->id), 
                    'ref'       => $ref], 
                    'method'    => 'put', 
                    'class'     => 'form-horizontal'
                    ]) !!}

                    @if (isset($_GET['idf']))
                        <input type="hidden" name="_idf" value="{{ e($_GET['idf']) }}">
                    @endif
                    <div class="panel-body ">
                        @var $client = $detail->client

                        @include('client.de.partials.inputs-quote')

                        <div class="text-right">
                            <a href="{{ route('de.edit', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}" class="btn border-slate text-slate-800 btn-flat">Cancelar</a>

                            {!! Form::button('Actualizar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
@endsection