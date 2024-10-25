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
                            class="text-semibold">Seguro de Multiriesgo</span></h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Multiriesgo</a></li>
                    <li class="active">Emisión</li>
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
                @if(session('success_client'))
                    <script>
                        $(function () {
                            messageAction('succes', "{{ session('success_client') }}");
                        });
                    </script>
                @endif

                @if(session('error_header'))
                    <div class="alert bg-danger alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{{ session('error_header') }}</span>.
                    </div>
                @endif

                <div class="panel-body ">
                    <div class="col-md-10 col-md-offset-1">
                        @if ($header->type === 'I')
                            <div class="page-header" style="padding: 5px;">
                                <h2>Póliza {{ $header->prefix }}-{{ $header->issue_number }}</h2>
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
                        <div class="panel panel-body border-top-success">
                            <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Nombre Completo: </strong>
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
                                        <strong>C.I.: </strong>
                                    </div>
                                    <div class="col-lg-5">
                                        {{ $header->client->dni }} {{ $header->client->extension }}
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-xs-10 col-md-4">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <strong>Fecha de Nacimiento: </strong>
                                    </div>
                                    <div class="col-lg-5">
                                        {{ dateToFormat($header->client->birthdate) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4 col-md-4 pull-right">
                                <a href="{{ route('td.client.i.edit', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    'client_id' => encode($header->client->id),
                                    'coverage'  => $de_id,
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
                                <h6 class="modal-title">Datos del Inmueble</h6>
                            </div>
                        </div>
                        <div class="panel panel-body border-top-success" ng-controller="DetailTdController">
                            <div class="col-xs-12 col-md-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Materia</th>
                                        <th>Descripción</th>
                                        <th>Número</th>
                                        <th>Uso</th>
                                        <th>Valor Asegurado</th>
                                        <th>Status</th>
                                        <th class="text-center">Accion</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($header->details as $key => $detail)
                                        <tr>
                                            <td><a href="#">{{ $detail->matter_insured_text }}</a></td>
                                            <td>{{ $detail->matter_description }}</td>
                                            <td>{{ $detail->number }}</td>
                                            <td>{{ $detail->use_text }}</td>
                                            <td>
                                                <strong>{{ number_format($detail->insured_value, 2) }} {{ $header->currency }}</strong>
                                            </td>
                                            <td>
                                                <span class="label label-success">Completado</span>
                                            </td>
                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li>
                                                                <a href="{{ route('td.pr.i.edit', [
                                                                    'rp_id'     => $rp_id,
                                                                    'header_id' => $header_id,
                                                                    'detail_id' => encode($detail->id),
                                                                    'coverage'  => $de_id,
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

                            <div class="text-right">
                                <a href="{{ route('td.pr.i.edit', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    'detail_id' => null,
                                    'coverage'  => $de_id,
                                ]) }}"
                                   ng-click="editIssuance($event)" class="btn btn-primary">
                                    <i class="icon-pencil3"
                                       ng-click="$event.stopPropagation(); $event.preventDefault()"></i>
                                    Agregar Inmueble</a></li>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10 col-md-offset-1">
                        <div class="modal-header bg-primary recuadro">
                            <div class="panel-heading">
                                <h6 class="modal-title">Datos del Crédito Solicitado</h6>
                            </div>
                        </div>

                        {!! Form::open(['route' => ['td.coverage.update',
                            'rp_id'         => $rp_id,
                            'de_id'         => $de_id,
                            'header_id'     => $header_id
                            ],
                            'method'        => 'put',
                            'class'         => 'form-horizontal',
                            // 'ng-controller' => 'HeaderAuController'
                        ]) !!}

                        <div class="panel panel-body border-top-success">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-4 label_required">
                                        <strong>Inicio de Vigencia:</strong>
                                    </label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                            {!! Form::text('validity_start', old('validity_start', dateToFormat($header->validity_start)), [
                                                'class'        => 'form-control pickadate-cobodate',
                                                'autocomplete' => 'off',
                                                'placeholder'  => 'Seleccione fecha'
                                            ]) !!}
                                        </div>
                                        <label id="location-error" class="validation-error-label"
                                               for="location">{{ $errors->first('validity_start') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-4">
                                        <strong>Moneda: </strong>
                                    </div>
                                    <div class="col-lg-8">
                                        {!! SelectField::input('currency', $data['currencies']->toArray(), [
                                            'class' => 'select-search',
                                            'id'    => 'currency',
                                          ],
                                            old('currency', $header->currency))
                                        !!}
                                        <label id="location-error" class="validation-error-label"
                                               for="location">{{ $errors->first('currency') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-4">
                                        <strong>Plazo del Crédito: </strong>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="col-lg-4">
                                            {!! Form::text('term', old('term', $header->term), [
                                                'class'        => 'form-control',
                                                'autocomplete' => 'off',
                                                'id'           => 'term',
                                            ]) !!}
                                            <label id="location-error" class="validation-error-label"
                                                   for="location">{{ $errors->first('term') }}</label>
                                        </div>
                                        <div class="col-lg-8">
                                            {!! SelectField::input('type_term', $data['term_types']->toArray(), [
                                                'class' => 'form-control',
                                                'id'    => 'type_term',
                                                ], old('type_term', $header->type_term)) !!}
                                            <label id="location-error" class="validation-error-label"
                                                   for="location">{{ $errors->first('type_term') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <hr/>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3 {{ $header->warranty ? 'label_required' : '' }}">
                                            Número de Operación: </label>
                                        <div class="col-lg-9">
                                            <div class="input-group">
                                                <input type="hidden" name="warranty"
                                                       value="{{ (int) $header->warranty }}">

                                                <span class="input-group-addon">Nro.</span>
                                                {!! Form::text('operation_number', old('operation_number', $header->operation_number), [
                                                    'class'        => 'form-control ui-wizard-content',
                                                    'autocomplete' => 'off',
                                                    'placeholder'  => 'Número de Crédito'])
                                                !!}
                                            </div>
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
                                            <button type="submit" class="btn btn-primary">Emitir <i
                                                        class="glyphicon glyphicon-floppy-disk position-right"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('de.issuance', [
                                                'rp_id'     => \Cache::get(decode($header_id)),
                                                'header_id' => $de_id,
                                                ]) }}" class="btn btn-info">
                                                Cancelar <i class="icon-warning position-right"></i>
                                            </a>
                                        @endif
                                    @elseif($header->type === 'I')
                                    @endif
                                </div>
                            </div>

                        </div>

                        {!! Form::close() !!}
                    </div>

                    <script type="text/javascript">
                        $(document).ready(function () {
                            $('#term').prop('readonly', true);

                            $('#currency option:not(:selected), ' +
                                    '#type_term option:not(:selected), ' +
                                    '#payment_method option:not(:selected)').prop('disabled', true);
                        });
                    </script>
                </div>
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
@endsection