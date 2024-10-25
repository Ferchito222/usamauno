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
                    <span class="text-semibold">Seguro de Ecovida / Ecoprotección</span>
                </h4>
                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Ecovida / Ecoprotección</a></li>
                    <li class="active">Cotizar</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row panel">
        <div class="col-md-12">
            <div ng-controller="HeaderElpController">
                <div class="panel-heading divhr">
                    <div class="steps-basic2 wizard">
                        <div class="steps">
                            <ul>
                                <li class="first done">
                                    <a href="#">
                                        <span class="number">1</span> Datos del Cliente
                                    </a>
                                </li>
                                <li class="first done">
                                    <a href="#">
                                        <span class="number">2</span> Resultado Cotización
                                    </a>
                                </li>
                                <li class="current">
                                    <a href="#">
                                        <span class="current-info audible">current step: </span>
                                        <span class="number">3</span> Emisión de la Póliza de Ecovida / Ecoprotección
                                    </a>
                                </li>
                                <li class="disabled last">
                                    <a href="#">
                                        <span class="number">4</span> Impresión de la Póliza
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button style="float: right;" type="button" class="btn btn-rounded btn-default text-right"
                            title="Detalle de producto" data-placement="right" data-toggle="modal"
                            data-target="#modal_theme_primary">
                        <i class="icon-question7"></i> Producto
                    </button>

                </div>
                <div class="clearfix"></div>

                @if(session('success_header'))
                    <script>
                        $(function () {
                            messageAction('succes', "{{ session('success_header') }}");
                        });
                    </script>
                @endif

                @if(session('error_header'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_header') }}");
                        });
                    </script>
                @endif

                @if(session('error_client'))
                    <script>
                        $(function () {
                            messageAction('info', "{{ session('error_client') }}");
                        });
                    </script>
                @endif

                @if(session('error_client_edit'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_client_edit') }}");
                        });
                    </script>
                @endif

                @if(session('success_client'))
                    <script>
                        $(function () {
                            messageAction('succes', "{{ session('success_client') }}");
                        });
                    </script>
                @endif

                @if(! is_null($header))
                    @if ($header->type === 'I')
                        <div class="page-header" style="padding: 5px;">
                            <h2>{{ $header->prefix }}-{{ $header->issue_number }}</h2>
                        </div>
                    @endif

                    <table class="table datatable-basic2">
                        <thead>
                        <tr>
                            <th>Cliente Ecofuturo</th>
                            <th>C.I.</th>
                            <th>Nombres y Apellidos</th>
                            <th>Fecha Nacimiento</th>
                            <th>Departamento</th>
                            <th>Información</th>
                            <th>Beneficiarios</th>
                            <th class="text-center">Accion</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $header->detail->client_eco ? 'SI' : 'NO' }}</td>
                            <td>
                                <a href="#">{{ $header->detail->client->dni }} {{ $header->detail->client->extension }}</a>
                            </td>
                            <td>{{ $header->detail->client->full_name }}</td>
                            <td>{{ dateToFormat($header->detail->client->birthdate) }}</td>
                            <td>{{ $header->detail->client->birth_place }}</td>
                            <td>
                                @if($header->detail->completed_client)
                                    <a href="{{ route('elp.client.edit', [
                                        'rp_id'     => $rp_id,
                                        'header_id' => $header_id,
                                    ]) }}"
                                       title="Pendiente" class="label label-success">
                                        Completado
                                    </a>
                                @else
                                    <a href="{{ route('elp.client.edit', [
                                        'rp_id'     => $rp_id,
                                        'header_id' => $header_id,
                                    ]) }}"
                                       title="Pendiente" class="label label-danger">
                                        Pendiente
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if(is_null($header->detail->beneficiary))
                                    <a href="{{ route('elp.beneficiary.create', [
                                            'rp_id'     => $rp_id,
                                            'header_id' => $header_id,
                                        ]) }}"
                                       title="Pendiente" class="label label-danger"
                                       ng-click="createBeneficiary($event)">
                                        Pendiente
                                    </a>
                                @else
                                    <a href="{{ route('elp.beneficiary.edit', [
                                            'rp_id'     => $rp_id,
                                            'header_id' => $header_id,
                                        ]) }}"
                                       title="Completado" class="label label-success"
                                       ng-click="editBeneficiary($event)">
                                        Completado
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <ul class="icons-list">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li>
                                                <a href="{{ route('elp.client.edit', [
                                                            'rp_id'     => $rp_id,
                                                            'header_id' => $header_id,
                                                        ]) }}"><i class="icon-plus2"></i> Editar datos</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        </tbody>
                        @endif
                    </table>

                    <div class="modal-header bg-primary title">
                        <div class="panel-heading">
                            <h6 class="modal-title">Información adicional</h6>
                        </div>
                    </div>

                    {!! Form::open(['route' => ['elp.update.issuance', 'rp_id' => $rp_id, 'header_id' => $header_id],
                        'method'  => 'put',
                        'class'   => 'form-horizontal',
                        'id'      => 'client-form',
                        'ng-init' => 'setPaymentMethod()',
                    ]) !!}

                    <input type="hidden" name="ice" id="ice" value="{{ (int) $header->detail->client_eco }}">

                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-12 text-semibold" style="text-align: justify;">
                                Desempeña algún cargo público y/o político jerárquico en el territorio nacional o país
                                extranjero
                            </label>
                            <label class="radio-inline" style="margin-left: 220px;">
                                {!! Form::radio('charge', 1, (old('charge', $header->detail->charge) == 1 ? true : false), [
                                    'class' => 'styled',
                                    'id'    => 'charge_1'
                                ]) !!} SI
                            </label>
                            <label class="radio-inline">
                                {!! Form::radio('charge', 0, (old('charge', $header->detail->charge) == 0 ? true : false), [
                                    'class' => 'styled',
                                    'id'    => 'charge_0'
                                ]) !!} NO
                            </label>
                            <label id="location-error" class="validation-error-label"
                                   for="location">{{ $errors->first('charge') }}</label>
                        </div>

                        <div class="form-group charge" style="display: none;">
                            <div class="form-group">
                                <label class="control-label col-lg-3 label_required">Cargo / Entidad: </label>
                                <div class="col-lg-9">
                                    {!! Form::textarea('charge_response', old('charge_response', $header->detail->charge_response), [
                                        'size'         => '4x2',
                                        'class'        => 'form-control',
                                        'placeholder'  => 'Cargo / Entidad',
                                        'autocomplete' => 'off',
                                        'id'           => 'charge_response',
                                    ]) !!}
                                    <label id="location-error" class="validation-error-label"
                                           for="location">{{ $errors->first('charge_response') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group charge" style="display: none;">
                            <div class="form-group">
                                <label class="control-label col-lg-3 label_required">Período cargo público: </label>
                                <div class="col-lg-9">
                                    {!! Form::textarea('charge_period', old('charge_period', $header->detail->charge_period), [
                                        'size'         => '4x2',
                                        'class'        => 'form-control',
                                        'placeholder'  => 'Período cargo público',
                                        'autocomplete' => 'off',
                                        'id'           => 'charge_period',
                                    ]) !!}
                                    <label id="location-error" class="validation-error-label"
                                           for="location">{{ $errors->first('charge_period') }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="control-label col-lg-3 label_required">Número de Póliza: </label>
                            <div class="col-lg-9">
                                {!! SelectField::input('policy_number', $data['policies'], [
                                    'class' => 'select-search',
                                ], old('policy_number', $header->policy_number)) !!}
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('policy_number') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3 label_required">Modalidad de Pago: </label>
                            <div class="col-lg-9">
                                {!! SelectField::input('payment_method', $data['payment_methods']->toArray(), [
                                    'class' => 'select-search',
                                    'id'    => 'payment_method',
                                ], old('payment_method', $header->detail->payment_method)) !!}
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('payment_method') }}</label>
                            </div>
                        </div>
                        @if($header->detail->client_eco)
                            @if($header->detail->client->accounts->count() > 0)
                                <div class="form-group">
                                    <label class="col-lg-3 control-label label_required">Nº de Cuenta: </label>
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-list"></i></span>
                                            <select name="number" class="select-search">
                                                @foreach($header->detail->client->accounts as $acc)
                                                    <option value="{{ $acc->number }}"
                                                            {{ $acc->number == old('number', $header->detail->client->accounts()->where('main_account', true)->first()->number) }}>
                                                        {{ $acc->number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label id="location-error" class="validation-error-label"
                                               for="location">{{ $errors->first('number') }}</label>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label col-lg-3 label_required">Moneda Operación: </label>
                                <div class="col-lg-9">
                                    {!! SelectField::input('currency', $data['currencies']->toArray(), [
                                        'class' => 'select-search',
                                    ], old('currency', $header->detail->currency)) !!}
                                    <label id="location-error" class="validation-error-label"
                                           for="location">{{ $errors->first('currency') }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-lg-3 label_required">Tipo de Cuenta: </label>
                                <div class="col-lg-9">
                                    <select name="account_type" class="select-search">
                                        <option value="">Seleccione</option>
                                        @foreach(config('base.account_types') as $key => $item)
                                            @if($key !== 'CA' && $key !== 'SA')
                                                <option value="{{ $key }}"
                                                        {{ $key === old('account_type', $header->detail->account_type) ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label id="location-error" class="validation-error-label"
                                           for="location">{{ $errors->first('account_type') }}</label>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="col-lg-3 control-label label_required">Nº de Boleta / Voucher: </label>
                                <div class="col-lg-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-list"></i></span>
                                        {!! Form::text('voucher', old('voucher', $header->detail->voucher), [
                                            'class'        => 'form-control ui-wizard-content',
                                            'placeholder'  => 'Nº de Boleta / Voucher',
                                            'autocomplete' => 'off',
                                        ]) !!}
                                    </div>
                                    <label id="location-error" class="validation-error-label"
                                           for="location">{{ $errors->first('voucher') }}</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-xs-12">
                        <div class="text-right">
                            @if($header->type === 'Q')
                                @if($header->completed)
                                    {!! Form::button('Guardar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                                @endif
                            @elseif($header->approved)
                                <a href="{{ route('home', []) }}" class="btn btn-info">
                                    Guardar y Cerrar <i class="icon-floppy-disk position-right"></i>
                                </a>

                                <a href="{{ route('elp.issuance', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                                   class="btn btn-primary">
                                    Emitir <i class="icon-play position-right"></i>
                                </a>
                            @endif
                        </div>
                        <br>
                    </div>
                    {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
