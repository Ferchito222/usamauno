@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    @include('partials.fields', ['type' => 'menu-header', 'product' => 'td'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal form -->
            <div class="panel panel-flat border-top-primary">
                @include('partials.step', ['step' => '3', 'product' => 'td'])
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
                                <h6 class="modal-title">Datos del Titular </h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success">
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
                            <a class="btn btn-default pull-right" href="{{ route('td.client.i.edit', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    'client_id' => encode($header->client->id),
                                     isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
                                ]) }}">
                                @if($header->client_completed)
                                    <span class="label label-success">Completado</span>
                                @else
                                    <span class="label label-danger">Pendiente</span>
                                @endif
                                Editar <i class="icon-pencil position-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-10 col-md-offset-1">
                        <div class="modal-header bg-primary recuadro">
                            <div class="panel-heading">
                                <h6 class="modal-title">Datos del Inmueble</h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success">
                            <div class="col-xs-12 col-md-12" ng-controller="DetailTdController">
                                <table class="table datatable small_body">
                                    <thead>
                                    <tr>
                                        <th>Materia</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Uso</th>
                                        <th>Valor Asegurado</th>
                                        <th>Estado</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($header->details as $key => $detail)
                                        <tr>
                                            <td><strong>{{ config('base.property_types.' . $detail->matter_insured) }}</strong></td>                                            <td>{{ $detail->matter_description }}</td>
                                            <td>{{ config('base.property_states.'.$detail->state) }}</td>
                                            <td>{{ config('base.property_uses.' . $detail->use) }}</td>
                                            <td><strong>{{ number_format($detail->insured_value, 2) }} {{ $header->currency }}</strong></td>
                                            <td>
                                                <a href="{{ route('td.mr.i.edit', ['rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => encode($detail->id)]) }}"
                                                   class="label label-{{$detail->completed?'success':'warning'}}"
                                                   ng-click="editIssuance($event)">{{$detail->completed?'Completado':'Pendiente'}}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li>
                                                                <a href="{{ route('td.mr.i.edit', ['rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => encode($detail->id)]) }}"
                                                                   ng-click="editIssuance($event)">
                                                                    <i class="icon-pencil3"
                                                                       ng-click="$event.stopPropagation(); $event.preventDefault()"></i>
                                                                    Editar</a>
                                                            </li>
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
                                <h6 class="modal-title">Datos Generales</h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success">
                            <div class="col-md-12 small_body">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-lg-5"><strong>Plazo Crédito: </strong></div>
                                        <div class="col-lg-7">{{ $header->term }} {{ config('base.term_types.'.$header->type_term) }}</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-lg-5"><strong>Inicio de Vigencia: </strong></div>
                                        <div class="col-lg-7">{{ date('Y-m-d',strtotime($header->validity_start)) }}</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <!--<div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <div class="col-lg-5"><strong>Forma de Pago: </strong></div>
                                        <div class="col-lg-7">{{ config('base.payment_methods.'.$header->payment_method) }}</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>-->
                            </div>
                            <div class="col-md-12">
                                <hr/>
                                <br />
                            </div>
                            
                            {!! Form::open(array('route' => ['td.update','rp_id' => $rp_id, 
                            'header_id' => $header_id], 
                            'method'        => 'put', 
                            'class'         => 'form-horizontal', 
                            'ng-controller' => 'HeaderTdController',
                            'class'=>'form-horizontal form-validate-jquery')) !!}
                            
                            <div class="col-md-12">
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
                                    <input type="hidden" name="warranty" value="{{ (int) $header->warranty }}">
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <u>Datos de <strong>Facturación</strong></u><br />
                                    @include('partials.fields', ['type'=>'text2','label'=>'Facturar a','id'=>'bill_name','required'=>1,'value'=>$header->client->full_name])
                                    <div class="clearfix"></div>
                                    <div class="col-md-{{$header->bill_dni != ''?'12':'7'}}">
                                        @include('partials.fields', ['type'=>'text2','label'=>'NIT','id'=>'bill_dni','required'=>1,
                                        'value'=>$header->bill_dni!=''?$header->bill_dni:$header->client->dni])
                                    </div>
                                    @if($header->bill_dni == false)
                                        <div class="col-md-5">
                                            @include('partials.fields', ['type'=>'text3','label'=>'Complemento NIT','id'=>'bill_complement','value'=>''])
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
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

                                        <a href="{{ route('td.issue', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                                           class="btn btn-primary">
                                            Emitir <i class="icon-play position-right"></i>
                                        </a>
                                    @else
                                        @if($header->facultative && ! $header->approved && ! $header->facultative_sent && ! isset($_GET['idf']))
                                            <a href="{{ route('td.fa.request.create', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
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
                            {!!Form::close()!!}
                        </div>

                    </div>
                </div>
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.list_content').click();
        });
    </script>
    <!-- modal -->
    @include('partials.modal')
            <!-- /modal -->
@endsection