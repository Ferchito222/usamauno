@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body" ng-controller="UserController">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label class="label label-primary no-margin" style="font-size: 100%;">Contraseña</label>
                        </div>
                        <div class="panel-body no-padding-top">
                            <div id="alert-pwd" class="alert no-border text-center" role="alert"></div>
                            {!! Form::open(['route' => ['user.options.update', 'option' => 'pwd'],
                                'method'    => 'put',
                                'class'     => 'form-horizontal',
                                'ng-submit' => 'optionsUpdate($event)',
                            ]) !!}
                            <div class="col-xs-12 col-md-3"></div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group input-group-xs">
                                    <label class="control-label col-lg-12 label_required">
                                        Escribe tu contraseña actual.
                                    </label>
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control input-xs"
                                               ng-model="formData.old_password">
                                        <label id="location-error" class="validation-error-label"
                                               ng-show="errors.old_password">
                                            @{{ errors.old_password[0] }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group input-group-xs">
                                    <label class="control-label col-lg-12 label_required">
                                        Escribe tu contraseña nueva.
                                    </label>
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control input-xs"
                                               ng-model="formData.password_new">
                                        <label id="location-error" class="validation-error-label"
                                               ng-show="errors.password_new">
                                            @{{ errors.password_new[0] }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group input-group-xs">
                                    <label class="control-label col-lg-12 label_required">
                                        Vuelve a escribir tu contraseña nueva.
                                    </label>
                                    <div class="col-lg-12">
                                        <input type="password" class="form-control input-xs"
                                               ng-model="formData.password_new_confirmation">
                                        <label id="location-error" class="validation-error-label"
                                               ng-show="errors.password_new_confirmation">
                                            @{{ errors.password_new_confirmation[0] }}
                                        </label>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-success">
                                        Guardar <i class="icon-floppy-disk position-right"></i>
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label class="label label-primary no-margin" style="font-size: 100%;">Agencia</label>
                        </div>
                        <div class="panel-body no-padding-top">
                            <div id="alert-agy" class="alert no-border text-center" role="alert"></div>
                            {!! Form::open(['route' => ['user.options.update', 'option' => 'agy'],
                                'method'    => 'put',
                                'class'     => 'form-horizontal',
                                'ng-submit' => 'optionsUpdate($event)',
                            ]) !!}
                            <div class="col-xs-12 col-md-3"></div>
                            <div class="col-xs-12 col-md-6" ng-init="formData.agency= '{{ $user->agency->id }}'">
                                <div class="form-group input-group-xs">
                                    <label class="control-label col-lg-12 label_required">
                                        Sucursal: <span class="text-bold">{{ $user->city->name }}</span>
                                    </label>
                                    <label class="control-label col-lg-12 label_required">
                                        Agencia.
                                    </label>
                                    <div class="col-lg-9">
                                        <select class="form-control select-xs" ng-model="formData.agency">
                                            @foreach($agencies as $agency)
                                                <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                            @endforeach
                                        </select>
                                        <label id="location-error" class="validation-error-label"
                                               ng-show="errors.agency">
                                            @{{ errors.agency[0] }}
                                        </label>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="submit" class="btn btn-success">
                                            Guardar <i class="icon-floppy-disk position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label class="label label-primary no-margin" style="font-size: 100%;">Teléfono de Agencia</label>
                        </div>
                        <div class="panel-body no-padding-top">
                            <div id="alert-tel" class="alert no-border text-center" role="alert"></div>
                            {!! Form::open(['route' => ['user.options.update', 'option' => 'tel'],
                                'method'    => 'put',
                                'class'     => 'form-horizontal',
                                'ng-submit' => 'optionsUpdate($event)',
                            ]) !!}
                            <div class="col-xs-12 col-md-3"></div>
                            <div class="col-xs-12 col-md-6" ng-init="formData.phone_number= '{{ $user->phone_number }}'">
                                <div class="form-group input-group-xs">
                                    <label class="control-label col-lg-12 label_required">
                                        Teléfono.
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control input-xs"
                                               ng-model="formData.phone_number">
                                        <label id="location-error" class="validation-error-label"
                                               ng-show="errors.phone_number">
                                            @{{ errors.phone_number[0] }}
                                        </label>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="submit" class="btn btn-success">
                                            Guardar <i class="icon-floppy-disk position-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection