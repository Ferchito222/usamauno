@extends('layout')

@section('class-container', 'login-container')

@section('content')
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @if(session('status'))
                                <div class="alert alert-success" role="alert">
                                <span class="display-block"
                                      style="font-weight: bold; font-size: 110%; text-align: center;">
                                    Comprueba tu correo electrónico y haz clic en el enlace.
                                </span>
                                    <br>
                                    Si no encuentras nuestro mensaje, comprueba la carpeta de spam.
                                </div>
                            @endif

                            <div class="text-center">
                                {!! Html::image('images/assets/abrenet-logo.png', '', ['width' => '160']) !!}

                                <h3 class="text-center">Cambia tu contraseña</h3>
                                <p>Introduce tu dirección de correo electrónico</p>
                                <div class="panel-body">
                                    {!! Form::open(['route' => 'password.create', 'method' => 'post']) !!}
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="glyphicon glyphicon-envelope color-blue"></i>
                                                </span>

                                                {!! Form::text('email', old('email'), [
                                                    'class'        => 'form-control',
                                                    'autocomplete' => 'off',
                                                    'maxlength'    => '140',
                                                ]) !!}
                                            </div>
                                            <label id="location-error" class="validation-error-label"
                                                   for="location">{{ $errors->first('email') }}</label>
                                        </div>
                                        <div class="form-group">
                                            <input class="btn btn-lg btn-primary btn-block" value="Continuar"
                                                   type="submit">
                                            <br>
                                            <a href="{{ auth()->check() ? route('auth.logout') : route('auth.login.get') }}">Volver</a>
                                        </div>
                                    </fieldset>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection