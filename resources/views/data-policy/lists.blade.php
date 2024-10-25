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
                <h4><i class="icon-arrow-right5 position-left"></i>
                    <span class="text-semibold">Edición de Pólizas</span>
                </h4>
            </div>

        </div>
    </div>
@endsection

@section('content')
    <div class="panel-body ">
        {!! Form::open(['route' => ['dp.search', ],
            'method'        => 'get',
            'class'         => 'form-horizontal',
            'ng-controller' => 'ReportController',
        ]) !!}

        <div class="panel-body">
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label class="control-label col-lg-3 label_required">Producto: </label>
                    <div class="col-lg-9">
                        <div class="input-group">
                            <select class="select-search" name="product" id="report-product">
                                <option value="">Seleccione...</option>
                                @foreach($data['retailerProducts'] as $retailerProduct)
                                    @if(in_array($retailerProduct->companyProduct->product->code, ['de','au','td','ema', 'rma']) && $retailerProduct->active == true)
                                        <option value="{{ encode($retailerProduct->id) }}"
                                                data-code="{{ $retailerProduct->companyProduct->product->code }}"
                                                {{ old('product') === encode($retailerProduct->id) ? 'selected' : '' }}>
                                            {{ $retailerProduct->companyProduct->product->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="input-group-addon"><i class="fa fa-th-list"></i></div>
                        </div>
                        <label id="location-error" class="validation-error-label"
                               for="location">{{ $errors->first('product') }}</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-lg-3">Cliente: </label>
                    <div class="col-lg-9">
                        <div class="input-group">
                            <input type="text" class="form-control" name="client"
                                   autocomplete="off" placeholder="Nombre del Cliente"
                                   value="{{ old('client') }}">
                            <div class="input-group-addon"><i class="fa fa-users"></i></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-lg-3">Usuario: </label>
                    <div class="col-lg-9">
                        <div class="input-group">
                            <select class="select-search" name="username">
                                <option value="">Todos</option>
                                @foreach($data['users'] as $user)
                                    <option value="{{ $user->username }}"
                                            {{ old('username') === $user->username ? 'selected' : '' }}>
                                        {{ $user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <label class="col-lg-4 control-label">Nro. de Póliza: </label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <div class="input-group-addon" id="product-code"></div>
                            <input type="text" class="form-control" name="number"
                                   autocomplete="off" placeholder="Nro. de Póliza"
                                   value="{{ old('number') }}">
                            <div class="input-group-addon"><i class="fa fa-file-text"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Documento de Identidad: </label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <input type="text" class="form-control" name="dni"
                                   autocomplete="off" placeholder="Documento de Identidad"
                                   value="{{ old('dni') }}">
                            <div class="input-group-addon"><i class="fa fa-credit-card"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Fecha de Emisión: </label>
                    <div class="col-lg-3" style="margin-right: 40px;">
                        <div class="input-group date">
                            <input type="date" class="form-control" name="date-start"
                                   autocomplete="off" placeholder="Desde"
                                   value="{{ old('date-start') }}" style="width: 160px;">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <input type="date" class="form-control" name="date-end"
                                   autocomplete="off" placeholder="Hasta"
                                   value="{{ old('date-end') }}" style="width: 160px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-primary center-block">Buscar
                <i class="icon-search4 position-right"></i>
            </button>
        </div>

        {{ csrf_field() }}

        {!! Form::close() !!}

        <hr>

        @if($headers->count() > 0 && strlen($code) > 0)
            @include('data-policy.rows-' . $code)

            {!! $headers->appends(request()->all())->render() !!}
        @endif
    </div>
@endsection

@include('partials.modal_content_report')