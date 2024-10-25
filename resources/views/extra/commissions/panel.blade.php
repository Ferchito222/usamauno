<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comisiones</title>

    {!! Html::style('assets/css/bootstrap.css') !!}

</head>
<body ng-app="sibas">
<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <div class="">
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="content-wrapper">
            <div class="panel text-center" style="color: white;">
                {!! Form::open(['route' => ['extra.commission.report', 'code' => request()->route()->getParameter('code')],
                    'method'        => 'get',
                    'class'         => 'form-horizontal',
                    'ng-controller' => 'ExtraController',
                    'target'        => '_blank',
                ]) !!}
                <div class="row" style="background: #1cb596;">
                    <div class="col-md-12">
                        <h3>Reporte de Comisiones <span class="label label-info">
                            {{ $retailerProduct->companyProduct->product->name }}
                        </span></h3>
                    </div>
                </div>
                <div class="row" style="background: #1cb596;">
                    <div class="col-md-4">
                        <h6>Tipo Reporte</h6>
                    </div>
                    <div class="col-md-4">
                        <h6>Período</h6>
                    </div>
                    <div class="col-md-4">
                        <h6>Reporte</h6>
                    </div>
                </div>
                <div class="row" style="background: white;">
                    <div class="col-md-4">
                        <select name="type" class="form-control input-sm" style="margin-top: 7px;" ng-model="type">
                            <option value="">Seleccione...</option>
                            @foreach(config('base.commission_types') as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" style="padding: 7px;" ng-if="daily">
                            <label class="control-label col-lg-12" style="background: #1cb596; text-align: center;">
                                Fecha
                            </label>
                            <div class="col-lg-12">
                                <input type="date" name="day" value="{{ date('Y-m-d') }}" class="form-control input-sm">
                            </div>
                        </div>
                        <div ng-if="monthly">
                            <div class="form-group" style="padding: 7px;">
                                <label class="control-label col-lg-6" style="background: #1cb596; text-align: center;">
                                    Mes
                                </label>
                                <label class="control-label col-lg-6" style="background: #1cb596; text-align: center;">
                                    Año
                                </label>
                                <div class="col-lg-6">
                                    <select name="month" class="form-control input-sm" style="margin-top: 7px;">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $date->month === $i ? 'selected' : '' }}>
                                                {{ monthTranslated($i) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <select name="monthly_year" class="form-control input-sm" style="margin-top: 7px;">
                                        @for($i = 2015; $i <= $date->year; $i++)
                                            <option value="{{ $i }}" {{ $date->year === $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div ng-if="campaign">
                            <div class="form-group" style="padding: 7px;">
                                <label class="control-label col-lg-6"
                                       style="background: #1cb596; text-align: center;">
                                    Campaña
                                </label>
                                <label class="control-label col-lg-6"
                                       style="background: #1cb596; text-align: center;">
                                    Año
                                </label>
                                <div class="col-lg-6">
                                    <select name="campaign" class="form-control input-sm" style="margin-top: 7px;">
                                        @foreach($campaigns as $key => $campaign)
                                            <option value="{{ $key }}">{{ $campaign['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <select name="campaign_year" class="form-control input-sm" style="margin-top: 7px;">
                                        @for($i = 2015; $i <= $date->year; $i++)
                                            <option value="{{ $i }}" {{ $date->year === $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info" ng-disabled="type_sl" style="margin: 10px;">
                            Generar Reporte
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

{!! Html::script('assets/js/core/libraries/jquery.min.js') !!}
{!! Html::script('js/app.js') !!}
</body>
</html>