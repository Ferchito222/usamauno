@extends('extra.commissions.layout')

@section('content')
    <table style="font-family:Arial; font-size:10pt; border-collapse: collapse; border: #F39C11 2px solid;" border="1">
        <tr style="border: #FFFFFF 1px solid; font-weight:bold;">
            <td colspan="24">
                <div style="color:#F39C11;">
                    <span>COMISIONES ECOSEGUROS - <span style="color: #F39C11;">Reporte Mensual</span></span>
                    <br>CAMPAÑA: " <span style="font-size:12pt;">
                        {{ $info['campaign']['number'] }}-{{ $info['date']->year }}</span> "
                    <span style="font-size:8pt; font-weight:normal;">
                        ({{ $info['campaign']['months']->implode('-') }}) / {{ $info['date']->year }}
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
        <tr style="background: #F39C11; color:white; text-align:center; font-size:8pt;">
            <th rowspan="2" style="border-top: #F39C11 1px solid;"></th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">PRODUCTO</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">SUCURSAL</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">AGENCIA</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">TIPO DE AGENCIA</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">NOMBRE VENDEDOR</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">CODIGO VENDEDOR</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">CARGO VENDEDOR</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">REFERIDO POR</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">Nº SEGUROS
                VENDIDOS
                <div style="font-size:7pt">(Campa&ntilde;a Anterior)</div>
            </th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">META MES</th>
            @foreach(explode('-', $info['campaign']['months']->implode('-')) as $key => $month)
                <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">
                    VENTAS MES {{ $key + 1 }} <br> ({{ $month }})
                </th>
            @endforeach
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">
                CUMPLIMIENTO <br>MES ACTUAL (%) <br> ({{ strtoupper(monthTranslated($info['date']->month)) }})
            </th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">META CAMPAÑA</th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">
                TOTAL VENTAS <br>ACUMULADAS CAMPAÑA
            </th>
            <th colspan="2"
                style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid; border-bottom: #F39C11 1px solid;">
                TIPO DE VENTAS
            </th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">
                CUMPLIMIENTO <br>CAMPAÑA (%)
            </th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">
                PERSISTENCIA <br> SEMESTRAL
            </th>
            <th rowspan="2" style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid;">
                PERSISTENCIA <br> ANUAL
            </th>
            <th colspan="2"
                style="border-left:#FFFFFF 2px solid; border-top: #F39C11 1px solid; border-bottom: #F39C11 1px solid;">
                RENOVACIONES
            </th>
        </tr>
        <tr style="background: #F39C11; color:white; text-align:center; font-size:8pt;">
            <th style="border-left:#FFFFFF 2px solid;"><b>ANUALES</b></th>
            <th style="border-left:#FFFFFF 2px solid;"><b>MENSUALES</b></th>
            <th style="border-left:#FFFFFF 2px solid;"><b>MENSUALES</b></th>
            <th style="border-left:#FFFFFF 2px solid;"><b>ANUALES</b></th>
        </tr>

        @var $users   = $headers;
        @var $i       = 1;
        @var $bg      = true;
        @var $rowspan = '';

        @foreach($headers as $key => $header)
            @if($key !== 'renovations')
                @if($rowspan === 'rowspan=2;')
                    @var $rowspan = 'display:none;';
                @elseif($headers->has($key + 1))
                    @var $rowspan = ( ($header->user_full_name === $headers[($key + 1)]->user_full_name) ? "rowspan=2;" : '' );
                @endif

                <tr style="vertical-align: middle; background: {{ $bg ? '#F7E2BE' : '#fff' }}; color: #4B4B4B;">
                    <td style="font-size: 6pt; border: #F39C11 1px solid;">{{ $i }}*</td>
                    <td style="border: #F39C11 1px solid;">{{ $header->product_text }}</td>
                    <td style="border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>{{ $header->city_name }}</td>
                    <td style="border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>{{ $header->agency_name }}</td>
                    <td style="border: #F39C11 1px solid; text-align: center; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->agency_type }}
                    </td>
                    <td style="border: #F39C11 1px solid; text-align: center; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->user_full_name }} {{ $header->user_detail > 0 ? 'x' . $header->user_detail : '' }}
                    </td>
                    <td style="text-align:center; border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->user_code }} {{ $header->user_position === 'CAJERO' ? ' ->' : '' }}
                    </td>
                    <td style="text-align:center; border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->user_position }} {{ $header->user_position === 'CAJERO' ? ' ->' : '' }}
                    </td>
                    <td style="border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                        {{ $header->reference_full_name }}
                    </td>
                    <td style="text-align:center; border: #F39C11 1px solid;">
                        {{ $header->campaign_previous }}
                    </td>

                    @if($info['campaign']['number'] === 1 && $info['date']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            {{ $header->target }}
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid;">
                            {{ $header->target }}
                        </td>
                    @endif

                    @foreach($header->sales as $k => $sale)
                        <td align="center"
                            style="border: #F39C11 1px solid; {{ $k === $info['date']->month ? 'background:#F8C472;' : '' }}">
                            {{ $sale }}
                        </td>
                    @endforeach

                    @if($info['campaign']['number'] === 1 && $info['date']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            @if($headers->has($key + 1) && $header->user_full_name === $headers[($key + 1)]->user_full_name)
                                {{ $header->compliment + $headers[($key + 1)]->compliment }} %
                            @else
                                {{ $header->compliment }} %
                            @endif
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid;">
                            {{ $header->compliment }} %
                        </td>
                    @endif

                    @if($info['campaign']['number'] === 1 && $info['date']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            {{ $header->target * 3 }}
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid;">
                            {{ $header->target * 3 }}
                        </td>
                    @endif

                    <td style="text-align:center; border: #F39C11 1px solid;" >
                        {{ $header->sales->sum() }}
                    </td>

                    <td style="text-align:center; border: #F39C11 1px solid;">
                        {{ $header['sale_types']['CO'] }}
                    </td>
                    <td style="text-align:center; border: #F39C11 1px solid;">
                        {{ $header['sale_types']['DA'] }}
                    </td>

                    @if($info['campaign']['number'] === 1 && $info['date']->year === 2016)
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid; {{ $rowspan }}" {{ $rowspan }}>
                            @if($headers->has($key + 1) && $header->user_full_name === $headers[($key + 1)]->user_full_name)
                                {{ $header->compliment_campaign + $headers[($key + 1)]->compliment_campaign }} %
                            @else
                                {{ $header->compliment_campaign }} %
                            @endif
                        </td>
                    @else
                        <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid;">
                            {{ $header->compliment_campaign }} %
                        </td>
                    @endif

                    <td style="text-align:center; border: #F39C11 1px solid;">
                        {{ empty($header->persistence_semester) ? '' : $header->persistence_semester }}
                    </td>
                    <td style="text-align:center; border: #F39C11 1px solid;">
                        {{ empty($header->persistence_annual) ? '' : $header->persistence_annual }}
                    </td>

                    <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid;">
                        {{ $header->renovations_monthly }}
                    </td>
                    <td style="text-align:center; vertical-align:middle; border: #F39C11 1px solid;">
                        {{ $header->renovations_annual }}
                    </td>
                </tr>

                @var $i  = $i + 1;

                @if($rowspan !== 'rowspan=2;')
                    @var $bg  = $bg ? false : true;
                @endif
            @endif
        @endforeach

        @if($headers['renovations']->count() > 0)
            <tr style="border: #FFFFFF 1px solid; font-size:8pt; font-style:italic;">
                <td colspan="24">
                    Emisiones Renovadas que se crearon este mes y que corresponden al mes siguiente: <br>

                    @foreach($headers['renovations'] as $key => $renovation)
                        {{ $renovation->product }} x {{ $renovation->Total }} <br>
                    @endforeach
                </td>
            </tr>
        @endif
    </table>
@endsection