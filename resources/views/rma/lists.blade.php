@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    @include('partials.fields', ['type' => 'menu-header', 'product' => 'rma'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                @include('partials.step', ['step' => '2', 'product' => 'rma'])
                @include('partials.messages')
                <div class="panel-body" ng-controller="DetailRmaController">
                    @var $parameter = $retailerProduct->parameters()->where('slug', 'GE')->first()
                    @if($parameter instanceof \Sibas\Entities\ProductParameter && $header->details->count() < $parameter->detail)
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::open(['route' => ['rma.vh.create', 'rp_id' => $rp_id, 'header_id' => $header_id], 
                            'method' => 'get', 
                            'class' => 'form-horizontal',
                            'ng-controller' => 'DetailRmaController',
                            'ng-submit'     => 'create($event)'
                            ]) !!}
                            <div class="form-group has-success">
                                <br />
                                <label class="control-label col-lg-4 text-semibold" style="text-align: right;">Busqueda de
                                    datos:</label>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-search4"></i></span>
                                        {!! Form::text('serial_number', old('serial_number'), [
                                            'class'        => 'form-control',
                                            'placeholder'  => 'NÚMERO DE SERIE',
                                            'autocomplete' => 'off',
                                            'id'  => 'serial_number',
                                        ]) !!}<br />
                                    </div>
                                    <span class="help-block">Ingrese el número de  serie de la maquinaria</span>
                                </div>
                                <div class="col-xs-12 col-md-3">
                                    {!! Form::button('Buscar <i class="icon-search4"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                            <div class="alert alert-danger no-border" ng-if="errors.message_search">
                                Por favor verificar que los datos de la garantía sean correctos en el sistema Web Garantias que maneja el banco.
                            </div>
                            <div class="alert alert-danger no-border" ng-if="errors.message_ws">
                                Ingrese número de  serie de la maquinaria
                            </div>
                        </div>
                    <div class="clearfix"></div>
                    <hr />
                    @endif
                    
                    <table class="table small_body">
                        <thead>
                        <tr>
                            <th>Número de Serie</th>
                            <th>Uso</th>
                            <th>Descripción</th>
                            <th>Ciudad</th>
                            <th>Valor Asegurado {{ $header->currency }}.</th>
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
                                                    <a href="{{ route('rma.detail.edit', ['rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => encode($detail->id)]) }}"
                                                       ng-click="edit($event)">
                                                        <i class="icon-pencil3"
                                                           ng-click="$event.stopPropagation(); $event.preventDefault()"></i>
                                                        Editar</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('rma.detail.destroy', ['rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => encode($detail->id)]) }}"
                                                       ng-click="delete($event)">
                                                        <i class="icon-trash"
                                                           ng-click="$event.stopPropagation(); $event.preventDefault()"></i>
                                                        Eliminar</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <br />
                    <div class="text-right">
                        @if($header->details->count() > 0)
                            <a href="{{ route('rma.result', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                               class="btn btn-primary">
                                Continuar <i class="icon-arrow-right14 position-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection