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
                    <li class="active">Cotizar</li>
                </ul>
            </div>

        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                <div class="panel-heading divhr">
                    <div class="steps-basic2 wizard">
                        <div class="steps">
                            @include('partials.steps', [ 'product' => 'de', 'step' => 1, 'length' => auth()->user()->username === 'guest' ? 3 : 5 ])
                        </div>
                    </div>
                    <button style="float: right;" type="button" class="btn btn-rounded btn-default text-right"
                            title="Detalle de producto" data-placement="right" data-toggle="modal"
                            data-target="#modal_theme_primary">
                        <i class="icon-question7"></i> Producto
                    </button>
                </div>
                <div class="clearfix"></div>

                @if(session('error_header'))
                    <div class="alert bg-danger alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">{{ session('error_header') }}</span>.
                    </div>
                @endif

                @if(session('success_cl'))
                    <div class="alert alert-warning alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">
                            No es necesario la emisión de un Certificado dado que la Operación Madre ya cuenta con seguro.
                        </span>
                    </div>
                @endif

                @if(session('success_header'))
                    <div class="alert alert-success alert-styled-right">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold">
                            {{ session('success_header') }}
                        </span>
                    </div>
                @endif

                <div class="panel-body ">
                    <div class="col-xs-8">
                        {!! Form::open(['route' => ['de.store', 'rp_id' => $rp_id],
                            'method'        => 'post',
                            'class'         => 'form-horizontal',
                            'ng-controller' => 'HeaderDeController',
                            'ng-submit'     => 'sendForm($event)',
                        ]) !!}
                        <div class="form-group">
                            <label class="control-label col-lg-3 label_required">Tipo de cobertura: </label>
                            <div class="col-lg-8">
                                {!! SelectField::input('coverage', $data['coverages']->toArray(), [
                                    'class' => 'form-control',
                                    'id'    => '_coverage',
                                ], old('coverage')) !!}
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('coverage') }}</label>
                            </div>
                            <div class="col-lg-1">
                                <a>
                                    <i class="icon-question7" data-toggle="tooltip" data-placement="right"
                                       data-html="true" data-container="body" title='<span style="font-weight: bold;">
                                       Individual - Mancomuno:</span>
                                       Cada uno de los deudores es responsable del 100% de la deuda. En caso de
                                       fallecimiento de uno de los responsables mancomunadamente por el crédito, la
                                       compañía indemnizara el 100% del capital asegurado al beneficiario a la primera
                                       muerte. <br><br> <strong>Codeudores</strong> <br> Cada codeudor es responsable
                                       del porcentaje cargado.'></i>
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Monto solicitado: </label>
                            <div class="col-lg-4">
                                {!! Form::text('amount_requested', 0, [
                                    'class'        => 'form-control',
                                    'autocomplete' => 'off'
                                ]) !!}
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('amount_requested') }}</label>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="icon-coins"></i>
                                    </div>
                                    {!! SelectField::input('currency', $data['currencies']->toArray(), [
                                        'class' => 'bootstrap-select'
                                    ], old('currency')) !!}
                                </div>
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('currency') }}</label>
                            </div>
                            <div class="col-lg-1">
                                <a>
                                    <i class="icon-question7" data-toggle="tooltip" data-placement="right"
                                       data-html="true" data-container="body" title='<h6 class="no-margin-top
                                       text-semibold">Monto solicitado</h6>
                                       Es el monto total del Crédito Solicitado en la presente operación.'></i>
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Plazo crédito: </label>
                            <div class="col-lg-4">
                                {!! Form::text('term', 0, [
                                    'class'        => 'form-control',
                                    'autocomplete' => 'off'
                                ]) !!}
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('term') }}</label>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-sort-time-asc"></i></span>
                                    {!! SelectField::input('type_term', $data['term_types']->toArray(), [
                                        'class' => 'bootstrap-select'
                                    ], old('type_term')) !!}
                                </div>
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('type_term') }}</label>
                            </div>
                            <div class="col-lg-1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-3 label_required">Producto: </label>
                            <div class="col-lg-8">
                                {!! SelectField::input('credit_product', $data['credit_products']->toArray(), [
                                    'class' => 'form-control',
                                    'id'    => '_credit_product'
                                ], old('credit_product')) !!}
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('credit_product') }}</label>
                            </div>
                            <!--<div class="col-lg-1">
                                <a>
                                    <i class="icon-question7" data-toggle="tooltip" data-placement="right"
                                       data-html="true" data-container="body" title='
                                       Solo el producto Asalariado 7x5 permite escoger la cobertura de Desempleo Involuntario.'>
                                    </i>
                                </a>
                            </div>-->
                        </div>
                        <div class="form-group" id="product_description_div" style="display: {{old('credit_product')!=5?'none':'block'}};">
                            <label class="control-label col-lg-3 label_required">Descripción del Producto: </label>
                            <div class="col-lg-8">
                                {!! Form::text('product_description', old('product_description'), [
                                    'class'        => 'form-control',
                                    'autocomplete' => 'off'
                                ]) !!}
                                <label id="location-error" class="validation-error-label"
                                       for="location">{{ $errors->first('product_description') }}</label>
                            </div>
                        </div>

                        @if($credit_line)
                            <div class="form-group">
                                <label class="control-label col-lg-3 label_required">Número de Línea Madre: </label>
                                <div class="col-lg-8">
                                    {!! Form::text('number_line', '', [
                                        'class'        => 'form-control',
                                        'autocomplete' => 'off'
                                    ]) !!}
                                    <label id="location-error" class="validation-error-label"
                                           for="location">{{ $errors->first('number_line') }}</label>
                                </div>
                            </div>

                            <input type="hidden" name="credit_line" value="1">

                            @if(session('success_cl') || ! is_null(old('op_line')))
                                <input type="hidden" name="op_line" value="1">

                                <div class="form-group">
                                    <label class="control-label col-lg-3 label_required">Número de Operación bajo línea: </label>
                                    <div class="col-lg-8">
                                        {!! Form::text('operation_number', '', [
                                            'class'        => 'form-control',
                                            'autocomplete' => 'off'
                                        ]) !!}
                                        <label id="location-error" class="validation-error-label"
                                               for="location">{{ $errors->first('operation_number') }}</label>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <input type="hidden" name="movement_type" value="FS">

                        <div class="text-right">
                            {!! Form::button((session('success_cl') || ! is_null(old('op_line')) ? 'Registrar Número de Operación' : 'Cotiza tu mejor seguro') . ' <i class="icon-arrow-right14 position-right"></i>', [
                                'type'  => 'submit',
                                'class' => 'btn btn-primary',
                            ]) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="col-xs-4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection