@extends('layout')

@section('header')
    
@endsection

@section('class-container', 'login-container')

@section('content')
    <style type="text/css">
        .material-switch > input[type="checkbox"] {
            display: none;
        }

        .material-switch > label {
            cursor: pointer;
            height: 0px;
            position: relative;
            width: 40px;
        }

        .material-switch > label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position:absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }
        .material-switch > label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }
        .material-switch > input[type="checkbox"]:checked + label::before {
            background: inherit;
            opacity: 0.5;
        }
        .material-switch > input[type="checkbox"]:checked + label::after {
            background: inherit;
            left: 20px;
        }
    </style>
    @include('partials.messages')
    {!! Form::open(['route' => ['client.card.record'],
        'method'        => 'post'
    ]) !!}
    <div class="panel panel-body login-form rounded-top10 rounded-bottom10">
        <div class="text-center">
            <h5 class="content-group">Tarjeta de Crédito</h5>
        </div>
        <label id="location-error" class="validation-error-label"
                   for="location">{!! $errors->first('dni') !!}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! Form::text('dni', old('dni'), [
                    'class'        => 'form-control',
                    'placeholder'  => 'CI',
                    'autocomplete' => 'off',
                    'maxlength'    => '140',
                ]) !!}
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
            </div>
        <label id="location-error" class="validation-error-label"
                   for="location">{!! $errors->first('extension') !!}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! SelectField::input('extension', $data['cities'], [
                    'class' => 'form-control',
                    ], old('extension')) 
                !!}
            </div>
        <div>
            {{ captcha_img('mini') }}
        </div>
            
            <label id="location-error" class="validation-error-label"
                   for="location">{!! $errors->first('captcha') !!}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! Form::text('captcha', old('captcha'), [
                    'class'        => 'form-control',
                    'placeholder'  => 'Ingrese codigo captcha',
                    'autocomplete' => 'off',
                    'maxlength'    => '140',
                ]) !!}
            </div>
        <div class="form-group">
            {!! Form::button('IMPRIMIR CERTIFICADO <i class="fa fa-file-pdf-o position-right"></i>', ['type' => 'submit', 'class' => 'btn bg-success btn-block rounded5']) !!}
        </div>
        <span class="help-block text-center no-margin">
            Si cuenta con un seguro puede descargarlo.
        </span>
    </div>
    {!! Form::close() !!}
@endsection
