@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    @include('partials.fields', ['type' => 'menu-header', 'product' => 'au'])
@endsection

@section('content')
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                @include('partials.step', ['step' => '2', 'product' => 'au'])
                @include('partials.messages')
                <div class="panel-body" ng-controller="DetailAuController">
                    @var $parameter = $retailerProduct->parameters()->where('slug', 'GE')->first()
                    @if($parameter instanceof \Sibas\Entities\ProductParameter && $header->details->count() < $parameter->detail)
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::open(['route' => ['au.vh.create', 'rp_id' => $rp_id, 'header_id' => $header_id], 
                            'method' => 'get', 
                            'class' => 'form-horizontal',
                            'ng-controller' => 'DetailAuController',
                            'ng-submit'     => 'create($event)'
                            ]) !!}
                            <div class="form-group has-success">
                                <br />
                                <label class="control-label col-lg-4 text-semibold" style="text-align: right;">Busqueda de
                                    datos:</label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-search4"></i></span>
                                        {!! Form::text('nro_ruat', old('nro_ruat'), [
                                            'class'        => 'form-control',
                                            'placeholder'  => 'Ruat o Placa',
                                            'autocomplete' => 'off',
                                            'id'  => 'nro_ruat',
                                        ]) !!}<br />
                                    </div>
                                    <span class="help-block">Ingrese Nro de Ruat o Placa</span>
                                </div>
                                <div class="col-lg-2">
                                    {!! SelectField::input('type_data', [['id'=>'ruat','name'=>'RUAT'],['id'=>'placa','name'=>'PLACA']], [
                                        'class' => 'form-control',
                                        'id'    => 'type_data',
                                        'name'    => 'type_data',
                                        ], old('type_data')) !!}
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
                                Ingrese Nro de Ruat o Placa
                            </div>
                        </div>
                    <div class="clearfix"></div><hr />
                    @endif
                    <table class="table small_body">
                        <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Vehículo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Cero Km.</th>
                            <th>Año</th>
                            <th>Placa</th>
                            <th>Categoria</th>
                            <th>Seguro con Daños Propios</th>
                            <th>Valor Asegurado USD.</th>
                            <th class="text-center">Accion</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($header->details as $key => $detail)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail->vehicleType->vehicle }}</td>
                                <td>{{ $detail->brand }}</td>
                                <td>{{ $detail->model }}</td>
                                <td>{{ $detail->mileage_text }}</td>
                                <td>{{ $detail->year }}</td>
                                <td>{{ $detail->license_plate }}</td>
                                <td><span class="label label-success">{{ $detail->category->category_name }}</span></td>
                                <td>{{ $detail->own_damage_insurance?'Si':'No' }}</td>
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
                                                    <a href="{{ route('au.vh.edit', ['rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => encode($detail->id)]) }}"
                                                       ng-click="edit($event)">
                                                        <i class="icon-pencil3"
                                                           ng-click="$event.stopPropagation(); $event.preventDefault()"></i>
                                                        Editar</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('au.vh.destroy', ['rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => encode($detail->id)]) }}"
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
                            <a href="{{ route('au.result', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                               class="btn btn-primary">
                                Continuar <i class="icon-arrow-right14 position-right"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    
@endsection