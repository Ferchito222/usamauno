@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    @include('partials.fields', ['type' => 'menu-header', 'product' => 'ema'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal form -->
            <div class="panel panel-flat border-top-primary">
                @include('partials.step', ['step' => '4', 'product' => 'ema'])
                @include('partials.messages')
                <div class="panel-body ">
                    <div class="col-md-10 col-md-offset-1">
                        @if ($header->type === 'I')
                            <div class="page-header" style="padding: 5px;">
                                <h2>Póliza {{ $header->prefix }} - {{$header->user->city->abbreviation}}{{ $header->issue_number }}</h2>
                            </div>

                            @if($header->facultative)
                                <div class="alert bg-warning alert-styled-right">
                            <span class="text-semibold">
                                Nota: Se deshabilitó el boton "Emitir" por las siguientes razones: <br>
                            </span>
                                    {!! $header->facultative_observation !!}
                                    <span class="text-semibold">
                                <br>
                                Por lo tanto debe solicitar aprobación de la Compañia de Seguros
                            </span>
                                </div>
                            @endif
                        @endif

                        <div class="modal-header bg-primary recuadro">
                            <div class="panel-heading">
                                <h6 class="modal-title">Información del CLIENTE</h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success small_body">
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>{{ ($header->client->type == 'N')?'Nombre Completo':'Nombre o Razón Social' }}: </strong>
                                    </div>
                                    <div class="col-lg-7">
                                        {{ $header->client->full_name }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-xs-8 col-md-4 border-blue">
                                <div class="form-group">
                                    <div class="col-lg-3">
                                        <strong>{{ ($header->client->type == 'N')?'C.I.':'NIT' }}: </strong>
                                    </div>
                                    <div class="col-lg-5">
                                        {{ $header->client->dni }} {{ ($header->client->type == 'N')?$header->client->extension:'' }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-xs-10 col-md-4">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>{{ ($header->client->type == 'N')?'Fecha de Nacimiento':'Fecha de Constitución' }}: </strong>
                                    </div>
                                    <div class="col-lg-5">
                                        {{ ($header->client->type == 'N')?dateToformat($header->client->birthdate):$header->client->birthdate }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4 col-md-4 pull-right">
                                <a href="{{ route('ema.client.i.edit', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    'client_id' => encode($header->client->id),
                                     isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
                                ]) }}"
                                   class="btn btn-default pull-right">
                                    @if($header->client_completed)
                                        <span class="label label-success">Completado</span>
                                    @else
                                        <span class="label label-danger">Pendiente</span>
                                    @endif
                                    Editar <i class="icon-pencil position-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10 col-md-offset-1">
                        <div class="modal-header bg-primary recuadro">
                            <div class="panel-heading">
                                <h6 class="modal-title">Datos del Riesgo</h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success">
                            <div class="col-xs-12 col-md-12 small_body" >
                                <table class="table" ng-controller="DetailEmaController">
                                    <thead>
                                    <tr>
                                        <th>Número de Serie</th>
                                        <th>Uso</th>
                                        <th>Descripción</th>
                                        <th>Ciudad</th>
                                        <th>Valor Comercial</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($header->details as $key => $detail)
                                        <tr>
                                            <td><strong>{{ $detail->serial_number }}</strong></td>
                                            <td>{{ $detail->property_use }}</td>
                                            <td>{{ $detail->description }}</td>
                                            <td>{{ $detail->city }}</td>
                                            <td>
                                                <strong>{{ number_format($detail->insured_value, 2) }} {{ $header->currency }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li>
                                                                <a href="{{ route('ema.detail.i.edit', [
                                                                    'rp_id'     => $rp_id,
                                                                    'header_id' => $header_id,
                                                                    'detail_id' => encode($detail->id),
                                                                    isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
                                                                ]) }}"
                                                                   ng-click="editIssuance($event)">
                                                                    <i class="icon-pencil3"
                                                                       ng-click="$event.stopPropagation(); $event.preventDefault()"></i>
                                                                    Editar</a></li>
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

                    <div class="col-md-10 col-md-offset-1">
                        <div class="modal-header bg-primary recuadro">
                            <div class="panel-heading">
                                <h6 class="modal-title">Datos del Crédito Solicitado</h6>
                            </div>
                        </div>

                        @if(! isset($_GET['idf']))
                            {!! Form::open(['route' => ['ema.update',
                                'rp_id'         => $rp_id,
                                'header_id'     => $header_id
                                ],
                                'method'        => 'put',
                                'class'         => 'form-horizontal',
                                'ng-controller' => 'HeaderEmaController'
                            ]) !!}
                        @else
                            {!! Form::open(['route' => ['ema.update.fa',
                                'rp_id'          => $rp_id,
                                'header_id'      => $header_id,
                                'id_facultative' => e($_GET['idf'])
                                ],
                                'method'         => 'put', 'class' => 'form-horizontal',
                                'ng-controller'  => 'HeaderEmaController'
                            ]) !!}
                        @endif
                        
                        <div class="panel panel-body border-top-success">
                            <span class="small_body">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <strong>Inicio de Vigencia: </strong>
                                        </div>
                                        <div class="col-lg-7">
                                            {{ dateToFormat($header->validity_start) }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <strong>Plazo del Crédito: </strong>
                                        </div>
                                        <div class="col-lg-7">
                                            {{ $header->term }} {{ config('base.term_types.' . $header->type_term) }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--<div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-lg-5">
                                            <strong>Forma de Pago: </strong>
                                        </div>
                                        <div class="col-lg-7">
                                            {{ config('base.payment_methods.' . $header->payment_method) }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>-->
                            </span>
                            <div class="col-xs-12 col-md-12">
                                <hr/><br />
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3 {{ $header->warranty ? 'label_required' : '' }}">Número de Operación: </label>
                                        <div class="col-lg-7">
                                            <input type="hidden" name="warranty" value="{{ (int) $header->warranty }}">
                                            <select name="operation_number" class="select-search">
                                                <option value="">Seleccione...</option>
                                                @foreach($header->client->operation_numbers as $operation_number)
                                                    <option value="{{ $operation_number }}" {{ ($operation_number == $header->operation_number) || ($operation_number === old('operation_number')) ? 'selected' : ''}}>{{ $operation_number }}</option>
                                                @endforeach
                                            </select>
                                            <label id="location-error" class="validation-error-label"
                                                   for="location">{{ $errors->first('operation_number') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label label_required">Número de Póliza: </label>
                                        <div class="col-lg-9">
                                            {!! SelectField::input('policy_number', $data['policies']->toArray(), [
                                                'class' => 'select-search'],
                                                old('policy_number', $header->policy_number))
                                            !!}
                                            <label id="location-error" class="validation-error-label"
                                                   for="location">{{ $errors->first('policy_number') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <br/>
                                <div class="text-right">
                                    @if($header->type === 'Q')
                                        @if($header->completed)
                                            <button type="submit" class="btn btn-primary">Guardar <i
                                                        class="glyphicon glyphicon-floppy-disk position-right"></i>
                                            </button>
                                        @endif
                                    @elseif($header->type === 'I')
                                        @if(! $header->facultative)
                                            <a href="{{ route('home', []) }}" class="btn btn-info">
                                                Guardar y Cerrar <i class="icon-floppy-disk position-right"></i>
                                            </a>

                                            <a href="{{ route('ema.issue', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                                               class="btn btn-primary">
                                                Emitir <i class="icon-play position-right"></i>
                                            </a>
                                        @else
                                            @if($header->facultative && ! $header->approved && ! $header->facultative_sent && ! isset($_GET['idf']))
                                                <a href="{{ route('ema.fa.request.create', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                                                   class="btn btn-warning"
                                                   ng-click="requestCreate($event)">
                                                    Solicitar aprobación de la Compañia
                                                    <i class="icon-warning position-right"
                                                       ng-click="$event.stopPropagation(); $event.preventDefault()"></i>
                                                </a>
                                            @else
                                                @if (! isset($_GET['idf']))
                                                    <a href="{{ route('home', []) }}" class="btn btn-info">
                                                        Solicitud enviada (Cerrar) <i
                                                                class="icon-warning position-right"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('home') }}"
                                                       class="btn border-slate text-slate-800 btn-flat">Cancelar</a>

                                                    {!! Form::button('Solicitud enviada (Guardar y Cerrar) <i class="icon-warning position-right"></i>', [
                                                        'type'  => 'submit',
                                                        'class' => 'btn btn-primary'
                                                    ]) !!}
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </div>

                        {!! Form::close() !!}
                    </div>


                </div>
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
@endsection