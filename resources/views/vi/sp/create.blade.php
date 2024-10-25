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
                            class="text-semibold">Seguro de Desgravamen</span></h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Desgravamen</a></li>
                    <li class="active">Sub Producto</li>
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
                            <span class="form-wizard-count">7</span>
                            Emisión de la Póliza de Vida
                            <small class="display-block">Sub-Producto</small>
                        </span>
                        <span class="col-md-1">
                            <button style="float: left;" type="button" class="btn btn-rounded btn-default text-right"
                                    data-popup="tooltip" title="Detalle de producto" data-placement="right"
                                    data-toggle="modal" data-target="#modal_theme_primary">
                                <i class="icon-question7"></i> Producto
                            </button>
                        </span>
                    </h6>
                </div>

                @if(session('error_header'))
                    <div class="alert bg-danger alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{{ session('error_header') }}</span>.
                    </div>
                @endif

                @if(session('success_header'))
                    <div class="alert bg-success alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{{ session('success_header') }}</span>.
                    </div>
                @endif

                <div class="col-md-10 col-md-offset-1">
                    <br>

                    @if ($error_value)
                        <div class="alert alert-danger" role="alert">
                            Imposible realizar la emisión.<br>
                            La suma total del valor asegurado para este cliente supera los
                            <strong>{{ number_format($amount_max, 2, '.', ',') }} Bs.</strong>
                            <br><br>
                            Haga clic <strong><a
                                        href="{{ route('de.issuance', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}">aquí</a></strong>
                            para volver a la pantalla de Emisión.
                        </div>
                    @endif
                    <div class="modal-header bg-primary title">
                        <div class="panel-heading">
                            <h6 class="modal-title">Información del TITULAR</h6>
                        </div>
                    </div>
                    <div class="panel panel-body border-top-success">
                        <div class="panel-heading">
                            <!--<h5 class="panel-title">Infomración del TITULAR</h5>-->
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <!--<li><a data-action="collapse"></a></li>
                                    <li><a data-action="reload"></a></li>
                                    <li><a data-action="close"></a></li>-->
                                </ul>
                            </div>
                        </div>
                        <!--<hr>-->
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Nombres: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->first_name }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Ap. Paterno: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->last_name }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Ap. Materno: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->mother_last_name }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Ap. de casada: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->married_name }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Estado Civil: </strong>
                                </div>
                                <div class="col-lg-7">
                                    @foreach($data['civil_status'] as $civil_status)
                                        @if($civil_status['id'] == $detail->client->civil_status)
                                            {{ $civil_status['name'] }}
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Tipo de documento:</strong>
                                </div>
                                <div class="col-lg-7">
                                    @foreach($data['document_type'] as $document_type)
                                        @if($document_type['id'] == $detail->client->document_type)
                                            {{ $document_type['name'] }}
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Documento de identidad: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->dni }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Complemento: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->complement }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Ext. Documento de identidad: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->extension }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>País: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->country }}
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
                                    <strong>Lugar de nacimiento: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->birth_place }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Lugar de residencia: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ ucwords(str_replace('-', ' ', $detail->client->place_residence)) }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Localidad: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->locality }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Dirección: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->home_address }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Ocupación (CAEDEC): </strong>
                                </div>
                                <div class="col-lg-7">
                                    @foreach($data['activities'] as $activity)
                                        @if($activity['id'] == $detail->client->ad_activity_id)
                                            {{ $activity['name'] }}
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Descripción ocupación: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->occupation_description }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Telefono 1: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->phone_number_home }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Telefono 2: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->phone_number_mobile }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Telef. Oficina: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->phone_number_office }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Correo electónico: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->client->email }}
                                </div>
                            </div>
                            <div class="clearfix"></div>
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
                            {{--<div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Monto Banca Comunal: </strong>
                                </div>
                                <div class="col-lg-7">
                                    Los Andes de America
                                </div>
                            </div>--}}
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="col-lg-5">
                                    <strong>Participación: </strong>
                                </div>
                                <div class="col-lg-7">
                                    {{ $detail->percentage_credit }} %
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::open(['route' => ['de.vi.sp.store', 'rp_id' => $rp_id, 'header_id' => $header_id, 'sp_id' => $sp_id],
                    'method'        => 'post',
                    'class'         => '',
                    'ng-controller' => 'HeaderViController',
                    'ng-init'       => 'getPlans()',
                    'ng-submit'     => 'sendForm($event)',
                ]) !!}

                {!! Form::hidden('detail_id', encode($detail->id)) !!}

                <div class="col-md-10 col-md-offset-1">
                    <div class="modal-header bg-primary title">
                        <div class="panel-heading">
                            <h6 class="modal-title">Cuestionario de Salud</h6>
                        </div>
                    </div>
                    <div class="panel panel-body border-top-success">
                        ¿Usted padece de alguna de las siguientes enfermedades?
                        <div class="col-xs-12 col-md-12">
                            @foreach($data['questions'] as $question)
                                <div class="form-group">
                                    <div class="col-xs-12 col-md-10">
                                        <label class="radio-inline text-semibold">
                                            <strong>{{ $question['order'] }}</strong>.
                                            {{ $question['question'] }}
                                        </label>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        {!! Form::hidden('qs[' . $question['order'] . '][id]', $question['id']) !!}
                                        {!! Form::hidden('qs[' . $question['order'] . '][question]', $question['question']) !!}
                                        <label class="radio-inline radio-right">
                                            {!! Form::radio('qs[' . $question['order'] . '][response]', '1', $question['check_yes'], ['class' => 'styled']) !!}
                                            Si
                                        </label>
                                        <label class="radio-inline radio-right">
                                            {!! Form::radio('qs[' . $question['order'] . '][response]', '0', $question['check_no'], ['class' => 'styled']) !!}
                                            No
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <label class="validation-error-label" for="location">{{ $errors->first('qs') }}</label>
                    </div>
                </div>

                <div class="clearfix">&nbsp;</div>
                <div class="panel-body form-horizontal">
                    <div>
                        <div class="modal-header bg-primary title">
                            <div class="panel-heading">
                                <h6 class="modal-title">
                                    Beneficiarios
                                </h6>
                            </div>
                        </div>

                        <div class="panel panel-body">
                            <div>
                                <a href="#" class="glyphicon glyphicon-minus-sign pull-right"
                                   style="color: #f44336; font-size: 25px;" ng-click="beneficiary($event, false)"></a>
                                <a href="#" class="glyphicon glyphicon-plus-sign pull-right"
                                   style="color: #4caf50; font-size: 25px;" ng-click="beneficiary($event, true)"></a>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="form-inline" id="beneficiaries">
                                @if (request()->old('beneficiaries'))
                                    <span ng-init="numberBN={{ count(request()->old('beneficiaries')) }}"></span>
                                    @foreach (request()->old('beneficiaries') as $key => $beneficiary)
                                        <div class="beneficiary">
                                            <span class="label label-info">{{ $key }}</span>
                                            <div class="form-group" style="margin-left: 5px;">
                                                <span class="label_required"></span>
                                                <input type="text" name="beneficiaries[{{ $key }}][first_name]"
                                                       class="form-control" autocomplete="off" placeholder="Nombre"
                                                       value="{{ old('beneficiaries.' . $key . '.first_name') }}">
                                                @if ($errors->first('beneficiaries.' . $key . '.first_name'))
                                                    <span class="validation-error-label"
                                                          for="location">Valor inválido</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <span class="label_required"></span>
                                                <input type="text" name="beneficiaries[{{ $key }}][last_name]"
                                                       class="form-control" autocomplete="off"
                                                       placeholder="Apellido Paterno"
                                                       value="{{ old('beneficiaries.' . $key . '.last_name') }}">
                                                @if ($errors->first('beneficiaries.' . $key . '.last_name'))
                                                    <span class="validation-error-label"
                                                          for="location">Valor inválido</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="beneficiaries[{{ $key }}][mother_last_name]"
                                                       class="form-control" autocomplete="off"
                                                       placeholder="Apellido Materno"
                                                       value="{{ old('beneficiaries.' . $key . '.mother_last_name') }}">
                                                @if ($errors->first('beneficiaries.' . $key . '.mother_last_name'))
                                                    <span class="validation-error-label"
                                                          for="location">Valor inválido</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <span class="label_required"></span>
                                                <input type="text" name="beneficiaries[{{ $key }}][relationship]"
                                                       class="form-control" autocomplete="off" placeholder="Parentesco"
                                                       value="{{ old('beneficiaries.' . $key . '.relationship') }}">
                                                @if ($errors->first('beneficiaries.' . $key . '.relationship'))
                                                    <span class="validation-error-label"
                                                          for="location">Valor inválido</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="beneficiaries[{{ $key }}][dni]"
                                                       class="form-control" autocomplete="off"
                                                       placeholder="Documento de Identidad"
                                                       value="{{ old('beneficiaries.' . $key . '.dni') }}">
                                                @if ($errors->first('beneficiaries.' . $key . '.dni'))
                                                    <span class="validation-error-label"
                                                          for="location">Valor inválido</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <span class="label_required"></span>
                                                <input type="text" name="beneficiaries[{{ $key }}][participation]"
                                                       class="form-control" autocomplete="off"
                                                       placeholder="Participación %"
                                                       value="{{ old('beneficiaries.' . $key . '.participation') }}">
                                                @if ($errors->first('beneficiaries.' . $key . '.participation'))
                                                    <span class="validation-error-label"
                                                          for="location">Valor inválido</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    @if (session('error_participation'))
                                        <label class="validation-error-label"
                                               for="location">{{ session('error_participation') }}</label>
                                    @endif
                                @endif
                            </div>
                            <label class="validation-error-label"
                                   for="location">{{ $errors->first('beneficiaries') }}</label>
                        </div>
                    </div>
                </div>

                <div class="panel-body form-horizontal">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Valor Asegurado: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon">Bs.</span>
                                    {!! Form::text('insured_value', old('insured_value', $detail->insured_value), [
                                        'class'        => 'form-control ui-wizard-content',
                                        'autocomplete' => 'off',
                                        'placeholder'  => 'Valor Asegurado',
                                        'id'           => 'insured_value',
                                        'readonly'     => true
                                    ]) !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('insured_value') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Forma de Pago: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-list-unordered"></i></span>
                                    {!! SelectField::input('payment_method', $data['payment_methods']->toArray(), [
                                        'class' => 'select-search',
                                        'id'    => 'payment_method',
                                      ],
                                        old('payment_method'))
                                    !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('payment_method') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Periodicidad: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-list-unordered"></i></span>
                                    {!! SelectField::input('period', $data['periods']->toArray(), [
                                        'class' => 'select-search',
                                        'id'    => 'period',
                                      ],
                                        old('period'))
                                    !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('period') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Número de Cuenta: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon">Nro</span>
                                    {!! Form::text('account_number', old('account_number'), [
                                        'class'        => 'form-control ui-wizard-content',
                                        'autocomplete' => 'off',
                                        'placeholder'  => 'Número de Cuenta',
                                        'id'           => 'account_number'
                                    ]) !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('account_number') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Tarjeta de Crédito: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-piggy-bank"></i></span>
                                    {!! Form::text('credit_card', old('credit_card'), [
                                        'class' => 'form-control ui-wizard-content',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Tarjeta de Crédito'])
                                    !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('credit_card') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Plan: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-list-unordered"></i></span>
                                    {!! SelectField::input('plan', $data['plans']->toArray(), [
                                        'class'     => 'select-search',
                                        'id'        => 'plans',
                                      ],
                                        old('plan'))
                                    !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('plan') }}</label>
                            </div>
                        </div>
                        <div class="modal-header bg-primary title">
                            <div class="panel-heading">
                                <h6 class="modal-title">Datos del Tomador</h6>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Nombre: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-piggy-bank"></i></span>
                                    {!! Form::text('taker_name', old('taker_name', $detail->client->full_name), [
                                        'class' => 'form-control ui-wizard-content',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Nombre del Tomador'])
                                    !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('taker_name') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">CI/NIT: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-piggy-bank"></i></span>
                                    {!! Form::text('taker_dni', old('taker_dni', $detail->client->dni), [
                                        'class' => 'form-control ui-wizard-content',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'CI/NIT del Tomador'])
                                    !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('taker_dni') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Número de domicilio: </label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-addon">Nro.</span>
                                    {!! Form::text('home_number', old('home_number', $detail->client->home_number), [
                                        'class' => 'form-control ui-wizard-content',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Número de domicilio'])
                                    !!}
                                </div>
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('home_number') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-3">Dirección laboral: </label>
                            <div class="col-lg-9">
                                {!! Form::textarea('business_address', old('business_address', $detail->client->business_address), [
                                    'size' => '4x4',
                                    'class' => 'form-control',
                                    'placeholder' => 'Dirección laboral',
                                    'autocomplete' => 'off'])
                                !!}
                                <label class="validation-error-label"
                                       for="location">{{ $errors->first('business_address') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        @if (! $error_value)
                            <a href="{{ route('de.vi.sp.list', ['rp_id' => $rp_id, 'header_id' => $header_id, 'sp_id' => $sp_id]) }}"
                               class="btn border-slate text-slate-800 btn-flat">Cancelar</a>

                            {!! Form::button('Guardar <i class="icon-floppy-disk position-right"></i>', [
                                'type' => 'submit',
                                'class' => 'btn btn-primary'])
                            !!}
                        @endif
                    </div>

                    <hr/>
                    <div class="col-xs-12 col-md-6" ng-if="plan">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="modal-header bg-success">
                                <h6 class="modal-title">@{{ plan.name }} </h6>
                            </div>
                            <div class="panel panel-body border-top-primary text-center">
                                <p class="text-muted content-group-sm">
                                    <span ng-repeat="p in plan.plan">
                                      @{{ p.cov }} @{{ p.rank | currency: 'Bs. ' }} <br>
                                    </span>
                                </p>
                                <div class="col-md-12">
                                    <p>
                                        Prima Anual Bs. @{{ plan.annual_premium | currency: 'Bs. ' }}<br>
                                        Prima Mensual Bs. @{{ plan.monthly_premium | currency: 'Bs. ' }} <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="rp-plans" value="{{ route('rp.plans', ['rp_id' => $sp_id]) }}">
                {!! Form::close() !!}
            </div>
            <!-- /horizotal form -->
        </div>
    </div>
@endsection
