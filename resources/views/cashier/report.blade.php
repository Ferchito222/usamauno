@extends('cashier.layout')

@section('content')
    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-content">
                    <div class="panel-body">
                        <h3 class="text-center">
                            <span class="label label-primary">
                                Reporte de Emisiones
                            </span>
                        </h3>
                        <br>

                        <div class="text-center">
                            {!! Form::open(['url' => request()->fullUrl(),
                                'method' => 'get',
                                'class'  => 'form-horizontal',
                            ]) !!}

                            <div class="form-group">
                                <label class="control-label col-xs-3" style="font-weight: bold;">Nro. de Certificado: </label>
                                <div class="col-xs-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">ECA</span>
                                        <input type="text" name="issue_number" value="{{ old('issue_number') }}"
                                               placeholder="Nro. de Certificado" autocomplete="off"
                                               class="form-control ui-wizard-content input-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-xs-3" style="font-weight: bold;">Documento de Identidad</label>
                                <div class="col-xs-2">
                                    <input type="text" name="dni" class="form-control" placeholder="Documento de Identidad"
                                           autocomplete="off" value="{{ old('dni') }}">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="complement" class="form-control" placeholder="Complemento"
                                           autocomplete="off" style="width: 110px;" value="{{ old('complement') }}">
                                </div>

                                <label class="control-label col-xs-1" style="font-weight: bold;">Extensión: </label>
                                <div class="col-xs-2">
                                    <div class="input-group">
                                        {!! SelectField::input('extension', $data['cities']['CI']->toArray(), [
                                            'class' => 'form-control',
                                        ], old('extension')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-xs-3" style="font-weight: bold;">Fecha de Emisión: </label>
                                <div class="col-xs-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                        {!! Form::text('date_begin', old('date_begin'), [
                                            'class'        => 'form-control pickadate-cobodate input-sm date',
                                            'placeholder'  => 'Fecha desde',
                                            'autocomplete' => 'off'
                                        ]) !!}
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                        {!! Form::text('date_end', old('date_end'), [
                                            'class'        => 'form-control pickadate-cobodate input-sm date',
                                            'placeholder'  => 'Fecha hasta',
                                            'autocomplete' => 'off'
                                        ]) !!}
                                    </div>
                                </div>
                            </div>

                            {{ csrf_field() }}

                            <button type="submit" class="btn btn-primary" style="margin-left: 20px;">Buscar</button>

                            {!! Form::close() !!}
                        </div>
                        <hr>
                    </div>

                    @include('cashier.rows-' . $code)

                    @if($headers->count() > 0)
                        {!! $headers->appends(request()->all())->render() !!}

                        <br>

                        <a href="{{ route(request()->route()->getName(), [
                            'code'   => $code,
                            'rp_id'  => $rp_id,
                            'format' => 'xls',
                            request()->getQueryString(),
                        ]) }}" class="btn btn-success">
                            Exportar a Excel
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.date').datepicker();
        });
    </script>
@endsection