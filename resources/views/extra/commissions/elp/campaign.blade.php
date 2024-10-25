@extends('extra.commissions.layout')

@section('content')
    <table style="font-family:Arial; font-size:10pt; border-collapse: collapse; border: #2A80B9 2px solid;" border="1">
        <tr style="border: #FFFFFF 1px solid; font-weight:bold;">
            <td colspan="33">
                <div style="color:#216095;">
                    <span>COMISIONES ECOSEGUROS - <span style="color: #216095;">Reporte por Campaña</span></span>
                    <br>CAMPAÑA: " <span style="font-size:12pt;">
                        {{ $info['campaign']['number'] }}-{{ $info['date_campaign']->year }}</span> "
                    <span style="font-size:8pt; font-weight:normal;">
                        ({{ $info['campaign']['months']->implode('-') }}) / {{ $info['date_campaign']->year }}
                    </span>
                    <br>
                    <span style="font-size:10pt; font-weight:normal;">
                        Mes Actual: {{ strtoupper(monthTranslated($info['date']->month)) }}
                    </span>
                    <span style="font-size:10pt; font-weight:normal; margin-left: 50px;">
                        Año Actual: {{ $info['date']->year }}
                    </span>
                </div>
            </td>
        </tr>
        <tr style="background: #2A80B9; color:white; text-align:center; font-size:8pt;">
            <th rowspan="2" style="border: #ffffff 1px solid; "></th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">PRODUCTO</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">SUCURSAL</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">AGENCIA</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">TIPO DE AGENCIA</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">NOMBRE VENDEDOR</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">CODIGO VENDEDOR</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">CARGO VENDEDOR</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">REFERIDO POR</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                Nº SEGUROS VENDIDOS
                <div style="font-size:7pt">(Campa&ntilde;a Anterior)</div>
            </th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">META MES</th>
            @foreach(explode('-', $info['campaign']['months']->implode('-')) as $key => $month)
                <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                    VENTAS MES {{ $key + 1 }} <br> ({{ $month }})
                </th>
            @endforeach
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                CUMPLIMIENTO MES ACTUAL (%) <br> ({{ strtoupper(monthTranslated($info['date']->month)) }})
            </th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">META CAMPAÑA</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                TOTAL VENTAS <br>ACUMULADAS CAMPAÑA
            </th>
            <th colspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">TIPO DE VENTAS</th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                CUMPLIMIENTO CAMPAÑA (%)
            </th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                PERSISTENCIA SEMESTRAL
            </th>
            <th rowspan="2" style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">PERSISTENCIA ANUAL</th>
            <th colspan="5" style="background: #1C6592; border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                COMISIONES
            </th>
            <th colspan="5" style="background: #1C6592; border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;">
                COMISIONES POR RENOVACIONES
            </th>
        </tr>
        <tr style="background: #2A80B9; color:white; text-align:center; font-size:8pt;">
            <td style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;"><b>ANUALES</b></td>
            <td style="border: #ffffff 1px solid; border-left:#FFFFFF 1px solid;"><b>MENSUALES</b></td>
            <td style="background: #1C6592;border-left:#FFFFFF 1px solid;"><b>NUEVAS ALTAS ANUALES</b></td>
            <td style="background: #1C6592;border-left:#FFFFFF 1px solid;"><b>NUEVAS ALTAS MENSUALES</b></td>
            <td style="background: #1C6592;border-left:#FFFFFF 1px solid;"><b>PERSISTENCIA SEMESTRAL</b></td>
            <td style="background: #1C6592;border-left:#FFFFFF 1px solid;"><b>PERSISTENCIA ANUAL</b></td>
            <td style="background: #1C6592;border-left:#FFFFFF 1px solid;"><b>TOTAL COMISIONES</b></td>
            <td style="border-left: #FFFFFF 1px solid;"><b>RENOVACIONES MENSUALES</b></td>
            <td style="border-left: #FFFFFF 1px solid;"><b>RENOVACIONES ANUALES</b></td>
            <td style="border-left: #FFFFFF 1px solid;"><b>Bs POR RENOVACION MENSUAL</b></td>
            <td style="border-left: #FFFFFF 1px solid;"><b>Bs POR RENOVACION ANUAL</b></td>
            <td style="border-left: #FFFFFF 1px solid;"><b>TOTAL RENOVACIONES (Bs)</b></td>
        </tr>

        @var $users   = $headers;
        @var $i       = 1;
        @var $bg      = true;
        @var $rowspan = '';

        @var $commissions_annual               = 0;
        @var $commissions_monthly              = 0;
        @var $commissions_persistence_semester = 0;
        @var $commissions_persistence_annual   = 0;
        @var $commissions_total                = 0;
        @var $renovations_commission_monthly   = 0;
        @var $renovations_commission_annual    = 0;
        @var $renovations_commission_total     = 0;


        @foreach($headers as $key => $header)
            @if($key !== 'total')
                @if($rowspan === 'rowspan=2;')
                    @var $rowspan = 'display:none;';
                @elseif($headers->has($key + 1))
                    @var $rowspan = ( ($header->user_full_name === $headers[($key + 1)]->user_full_name) ? "rowspan=2;" : '' );
                @endif

                <tr style="vertical-align: middle; background: {{ $bg ? '#E2EBFA' : '#fff' }}; color: #4B4B4B;">
                    <td style="font-size:6pt; vertical-align:middle;">{{ $i }}*</td>
                    <td style="border: #2A80B9 1px solid;">{{ $header->product_text }}</td>
                    <td style="border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>{{ $header->city_name }}</td>
                    <td style="border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>{{ $header->agency_name }}</td>
                    <td style="border: #2A80B9 1px solid; text-align: center; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->agency_type }}
                    </td>
                    <td style="border: #2A80B9 1px solid; text-align: center; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->user_full_name }} {{ $header->user_detail > 0 ? 'x' . $header->user_detail : '' }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->user_code }} {{ $header->user_position === 'CAJERO' ? ' ->' : '' }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->user_position }} {{ $header->user_position === 'CAJERO' ? ' ->' : '' }}
                    </td>
                    <td style="border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->reference_full_name }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid;">
                        {{ $header->campaign_previous }}
                    </td>

                    @if($info['campaign']['number'] === 1 && $info['date_campaign']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            {{ $header->target }}
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                            {{ $header->target }}
                        </td>
                    @endif

                    @foreach($header->sales as $k => $sale)
                        <td align="center" style="border: #2A80B9 1px solid;">{{ $sale }}</td>
                    @endforeach

                    @if($info['campaign']['number'] === 1 && $info['date_campaign']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            @if($headers->has($key + 1) && $header->user_full_name === $headers[($key + 1)]->user_full_name)
                                {{ $header->compliment + $headers[($key + 1)]->compliment }} %
                            @else
                                {{ $header->compliment }} %
                            @endif
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                            {{ $header->compliment }} %
                        </td>
                    @endif

                    @if($info['campaign']['number'] === 1 && $info['date_campaign']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            {{ $header->target * 3 }}
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                            {{ $header->target * 3 }}
                        </td>
                    @endif

                    <td style="text-align:center; border: #2A80B9 1px solid;" >
                        {{ $header->sales->sum() }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid;">
                        {{ $header['sale_types']['CO'] }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid;">
                        {{ $header['sale_types']['DA'] }}
                    </td>

                    @if($info['campaign']['number'] === 1 && $info['date_campaign']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            @if($headers->has($key + 1) && $header->user_full_name === $headers[($key + 1)]->user_full_name)
                                {{ $header->compliment_campaign + $headers[($key + 1)]->compliment_campaign }} %
                            @else
                                {{ $header->compliment_campaign }} %
                            @endif
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                            {{ $header->compliment_campaign }} %
                        </td>
                    @endif

                    <td style="text-align:center; border: #2A80B9 1px solid;">
                        {{ empty($header->persistence_semester) ? '' : $header->persistence_semester }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid;">
                        {{ empty($header->persistence_annual) ? '' : $header->persistence_annual }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid; background: #95C6E7;">
                        {{ empty($header->commission_annual) ? '' : $header->commission_annual }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid; background: #95C6E7;">
                        {{ empty($header->commission_monthly) ? '' : $header->commission_monthly }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid; background: #95C6E7;">
                        {{ empty($header->commission_persistence_semester) ? '' : $header->commission_persistence_semester }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid; background: #95C6E7;">
                        {{ empty($header->commission_persistence_annual) ? '' : $header->commission_persistence_annual }}
                    </td>
                    <td style="text-align:center; border: #2A80B9 1px solid; background: #95C6E7;">
                        {{ empty($header->commission_total) ? '' : $header->commission_total }}
                    </td>
                    <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                        {{ $header->renovations_monthly }}
                    </td>
                    <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                        {{ $header->renovations_annual }}
                    </td>
                    <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                        {{ empty($header->commission_renovation_monthly) ? '' : $header->commission_renovation_monthly }}
                    </td>
                    <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                        {{ empty($header->commission_renovation_annual) ? '' : $header->commission_renovation_annual }}
                    </td>
                    <td style="text-align:center; vertical-align:middle; border: #2A80B9 1px solid;">
                        {{ empty($header->commission_renovation_total) ? '' : $header->commission_renovation_total }}
                    </td>
                </tr>

                @var $i  = $i + 1;

                @if($rowspan !== 'rowspan=2;')
                    @var $bg  = $bg ? false : true;
                @endif
            @endif
        @endforeach

        <tr style="background: #1C6592; color: #ffffff; font-weight:bold; border: #1C6592 1px solid;" >
            <td style="font-size:6pt; border: #1C6592 1px solid;"></td>
            <td style="border: #1C6592 1px solid;"></td>
            <td style="border: #1C6592 1px solid;"></td>
            <td style="border: #1C6592 1px solid;"></td>
            <td style="border: #1C6592 1px solid;"></td>
            <td style="text-align:center;border: #1C6592 1px solid;"></td>
            <td style="border: #1C6592 1px solid;"></td>
            <td style="border: #1C6592 1px solid;"></td>
            <td style="border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td align="center" style="border: #1C6592 1px solid;"></td>
            <td align="center" style="border: #1C6592 1px solid;"></td>
            <td align="center" style="border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['commissions_annual'] }}
            </td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['commissions_monthly'] }}
            </td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['commissions_persistence_semester'] }}
            </td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['commissions_persistence_annual'] }}
            </td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['commissions_total'] }}
            </td>
            <!-- Renovaciones -->
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;"></td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['renovations_commission_monthly'] }}
            </td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['renovations_commission_annual'] }}
            </td>
            <td style="text-align:center; border: #1C6592 1px solid;">
                {{ $headers['total']['renovations_commission_total'] }}
            </td>
        </tr>
    </table>
@endsection