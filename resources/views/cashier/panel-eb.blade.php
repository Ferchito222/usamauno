@extends('cashier.layout')

@section('content')
    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-content">
                    <div class="panel-body">
                        <h3 class="text-center">
                            <span class="label label-primary">
                                Busqueda de Ordenes de Cobro
                            </span>
                        </h3>
                        <br>

                        <div class="text-center">
                            {!! Form::open(['route' => ['cashiers::order.search', 'code' => $code, 'rp_id' => $rp_id],
                                'method' => 'post',
                                'class'  => 'form-inline',
                            ]) !!}

                            <div class="form-group {{ $errors->first('issue_number') ? 'has-error' : '' }}">
                                <label style="font-weight: bold;">ECA-</label>
                                <input type="text" name="issue_number" class="form-control"
                                       placeholder="Nº de Certificado" autocomplete="off"
                                       style="width: 130px;" value="{{ old('issue_number') }}">
                            </div>
                            <div class="form-group {{ $errors->first('dni') ? 'has-error' : '' }}"
                                 style="margin-left: 5px;">
                                <label style="font-weight: bold;">Documento de Identidad</label>
                                <input type="text" name="dni" class="form-control" placeholder="Documento de Identidad"
                                       autocomplete="off" value="{{ old('dni') }}">
                            </div>
                            <div class="form-group ">
                                <label style="font-weight: bold;"> - </label>
                                <input type="text" name="complement" class="form-control" placeholder="Complemento"
                                       autocomplete="off" style="width: 110px;" value="{{ old('complement') }}">
                            </div>
                            <div class="form-group {{ $errors->first('extension') ? 'has-error' : '' }}"
                                 style="margin-left: 5px;">
                                <label style="font-weight: bold;">Extensión</label>
                                {!! SelectField::input('extension', $data['cities']['CI']->toArray(), [
                                    'class' => 'form-control',
                                ], old('extension')) !!}
                            </div>

                            <button type="submit" class="btn btn-primary" style="margin-left: 20px;">Buscar</button>

                            {!! Form::close() !!}
                        </div>
                        <hr>
                    </div>

                    @if(! is_null($header))
                        <table class="table table-bordered table-striped table-condensed" ng-controller="HeaderEbController">
                            <thead>
                            <tr>
                                <th class="text-center">Nº de Certificado</th>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">CI</th>
                                <th class="text-center">Fecha de Nacimiento</th>
                                <th class="text-center">Teléfono</th>
                                <th class="text-center">Prima</th>
                                <th class="text-center">Creado Por</th>
                                <th class="text-center">Fecha de Ingreso</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">{{ $header->prefix }}-{{ $header->issue_number }}</td>
                                <td class="text-center">{{ $header->detail->client->full_name }}</td>
                                <td class="text-center">
                                {{ $header->detail->client->dni }} {{ $header->detail->client->complement }} {{ $header->detail->client->extension }}
                                </td>
                                <td class="text-center">{{ $header->detail->client->birthdate->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $header->detail->client->phone_number_home }}</td>
                                <td class="text-center">30.00 Bs</td>
                                <td class="text-center">{{ $header->user->full_name }}</td>
                                <td class="text-center">{{ $header->created_at->format('d/m/Y h:i a') }}</td>
                                <td class="text-center">
                                    @if($header->issued)
                                        <a href="{{ route('certificate.show', [
                                            'code'      => 'eb',
                                            'rp_id'     => $rp_id,
                                            'type'      => 'certificate',
                                            'header_id' => encode($header->id),
                                            'format'    => 'print',
                                        ]) }}" class="btn btn-info" ng-click="openCertificate($event)">
                                            Ver Certificado
                                        </a>
                                    @else
                                        <a href="{{ route('cashiers::order.edit', [
                                            'code'      => $code,
                                            'rp_id'     => $rp_id,
                                            'header_id' => encode($header->id),
                                        ]) }}" class="btn btn-success" ng-click="orderEdit($event)">Emitir</a>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-body">
            <table class="table">
                <tr>
                    <td class="text-left">{!! Html::image($rp->companyProduct->company->image, '', ['width' => '120']) !!}</td>
                    <td class="text-right">{!! Html::image('images/ecoaguinaldo.jpg', '', ['width' => '120']) !!}</td>
                </tr>
            </table>

            <p style="margin-top: 5px;">
                <span style="font-weight: bold;">Ecoaguinaldo</span>
                <br>
                Producto diseñado para cualquier usuario de SFA'S del Banco Pyme Ecofuturo, donde pueden encontrar un
                seguro pensado para ellos y pueden ver que el Banco quiere acompañarlos y protegerlos en el tiempo.
                <br>
                <span style="font-weight: bold;">Prima: </span> Bs. 30.-
                <br><br>
                <span style="font-weight: bold;">Coberturas:</span>
            </p>

            <table class="table">
                <tr>
                    <td style="width: 25%;"></td>
                    <td style="width: 25%; text-align: center; border: 1px solid #f79646; background-color: #f79646;">
                        Descripción
                    </td>
                    <td style="width: 25%; text-align: center; border: 1px solid #f79646; background-color: #f79646;">
                        Suma Asegurada
                    </td>
                    <td style="width: 25%;"></td>
                </tr>
                <tr>
                    <td style=""></td>
                    <td style="text-align: center; border: 1px solid #f79646;">Muerte por cualquier causa</td>
                    <td style="text-align: center; border: 1px solid #f79646;">Bs. 5.80.-</td>
                    <td style=""></td>
                </tr>
            </table>

        </div>
    </div>
@endsection