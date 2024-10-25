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
                    <span class="text-semibold">Reportes Gráficos</span>
                </h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li class="active">Reportes Gráficos</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row" ng-controller="ReportController" ng-init="reportGraphic();">
        <div class="col-md-12">
            <div class="panel panel-body">
                <h3 class="label label-default" style="font-size: 100%;">Datos de Búsqueda</h3>

                {!! Form::open(['route' => ['report.graphic.data'],
                    'method'    => 'get',
                    'class'     => 'form-inline',
                    'ng-submit' => 'reportGraphicSearch($event)',
                    'id'        => 'form-report-graphic',
                ]) !!}

                <div class="form-group" ng-init="formData.type='sale'">
                    @foreach($types as $key => $type)
                        <label class="radio-inline">
                            <input type="radio" name="type" ng-model="formData.type"
                                   value="{{ $key }}" > {{ $type['title'] }}
                        </label>
                    @endforeach
                </div>
                <br>

                <div class="form-group">
                    <label class="control-label">Sucursal: </label>
                    {!! SelectField::input('city', $data['cities'], [
                        'class'       => 'form-control',
                        'placeholder' => 'Sucursal',
                        'ng-init'     => 'formData.city=""',
                        'ng-model'    => 'formData.city',
                    ], old('city')) !!}
                </div>

                <div class="form-group" style="margin-left: 50px;">
                    <label class="control-label">Fecha: </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                        {!! Form::text('date_begin', old('date_begin'), [
                            'class'        => 'form-control pickadate-cobodate input-sm',
                            'placeholder'  => 'Fecha desde',
                            'autocomplete' => 'off',
                            'ng-init'      => 'formData.date_begin=""',
                            'ng-model'     => 'formData.date_begin',
                        ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                        {!! Form::text('date_end', old('date_end'), [
                            'class'        => 'form-control pickadate-cobodate input-sm',
                            'placeholder'  => 'Fecha hasta',
                            'autocomplete' => 'off',
                            'ng-init'      => 'formData.date_end=""',
                            'ng-model'     => 'formData.date_end',
                        ]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Generar Gráfica <i class="icon-arrow-right14 position-right"></i>
                    </button>
                </div>

                {!! Form::close() !!}
            </div>
            <div class="panel panel-flat border-top-primary">
                <div class="panel-body">
                    <div id="container" data-url="{{ route('report.graphic.data') }}">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">@{{ modal_title }}</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Emisiones</th>
                                <th>Cesantía</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="rs in user_results">
                                <td>@{{ rs.user }}</td>
                                <td>@{{ rs.issuance }}</td>
                                <td>@{{ rs.unemployment }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection