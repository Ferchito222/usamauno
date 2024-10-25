@extends('layout')

@section('header')
    @include('auth.header-login')
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

    {!! Form::open(['route' => ['auth.login.post'],
        'method'        => 'post',
        'ng-controller' => 'UserController',
        'ng-init'       => 'guest=' . ((boolean) old('guest') ? (boolean) old('guest') : '0'),
    ]) !!}
    <div class="panel panel-body login-form">
        <div class="text-center">
            <h5 class="content-group">Acceda a su cuenta
                <small class="display-block">Sus credenciales</small>
            </h5>
        </div>

        <div ng-hide="guest">
            <label id="location-error" class="validation-error-label"
                   for="location">{!! $errors->first('username') !!}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! Form::text('username', old('username'), [
                    'class'        => 'form-control',
                    'placeholder'  => 'Usuario',
                    'autocomplete' => 'off',
                    'maxlength'    => '140',
                ]) !!}
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
            </div>

            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('password') }}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! Form::password('password', [
                    'class'        => 'form-control',
                    'placeholder'  => 'Contraseña',
                    'autocomplete' => 'off',
                    'maxlength'    => '60',
                ]) !!}
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
            </div>

            <div class="form-group login-options">
                <div class="row">
                    <div class="col-sm-6">
                        <label class="checkbox-inline">
                            {!! Form::checkbox('remember', null, false, ['class' => 'styled']) !!}
                            Recuérdame
                        </label>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('password.create') }}">¿Has olvidado tu contraseña?</a>
                    </div>
                </div>
            </div>
        </div>

        <div ng-show="guest">
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('first_name') }}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! Form::text('first_name', old('first_name'), [
                    'class'        => 'form-control',
                    'placeholder'  => 'Nombres',
                    'autocomplete' => 'off',
                    'maxlength'    => '140',
                ]) !!}
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
            </div>

            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('last_name') }}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! Form::text('last_name', old('last_name'), [
                    'class'        => 'form-control',
                    'placeholder'  => 'Apellidos',
                    'autocomplete' => 'off',
                    'maxlength'    => '140',
                ]) !!}
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
            </div>

            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('email') }}</label>
            <div class="form-group has-feedback has-feedback-left">
                {!! Form::email('email', old('email'), [
                    'class'        => 'form-control',
                    'placeholder'  => 'mail@email.com',
                    'autocomplete' => 'off',
                    'maxlength'    => '140',
                ]) !!}
                <div class="form-control-feedback">
                    <i class="icon-mail5 text-muted"></i>
                </div>
            </div>

            <label id="location-error" class="validation-error-label">{{ $errors->first('city') }}</label>
            <div class="form-group">
                {!! Form::select('city', $cities, null, [
                    'class' => 'form-control',
                ]) !!}
            </div>
        </div>

        <div class="form-group login-options">
            <div class="row">
                <div class="col-sm-8 text-right" style="font-weight: bold;">
                    INVITADO
                </div>
                <div class="col-sm-4">
                    <div class="material-switch pull-right">
                        <input id="someSwitchOptionSuccess" name="guest" type="checkbox" value="1" ng-model="guest"
                               ng-true-value="1" ng-false-value="0">
                        <label for="someSwitchOptionSuccess" class="label-success"></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::button('Iniciar sesión <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn bg-blue btn-block']) !!}
        </div>
        <span class="help-block text-center no-margin">Al continuar , usted confirma que ha leído nuestros Terminos &amp; Condiciones</span>
    </div>
    {!! Form::close() !!}
@endsection
