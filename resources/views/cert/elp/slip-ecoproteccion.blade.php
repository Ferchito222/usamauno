<div style="width: 775px; height: auto; border: 0 none; padding: 10px;">
    <div style="width: 770px; font-weight: normal; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
        @var $font_parent = 'font-size:80%;'
        @var $font_child = 'font-size:100%;'
        @if($format === 'pdf')
            @var $font_parent = 'font-size:60%;'
            @var $font_child = 'font-size:60%;'
        @endif

        <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; {{$font_parent}} font-family: Arial;">
                <tr>
                    <td colspan="3" style="width:100%;" align="center">
                        <img src="{{ asset('images/ecoproteccion.jpg') }}">
                    </td>
                </tr>
                <tr>
                    <td style="width:34%;" align="left">SLIP DE COTIZACIÓN<br>ECS - {{ $header->quote_number }}</td>
                    <td style="width:32%;"></td>
                    <td style="width:34%;" align="right">Cotización válida hasta
                        el: {{ date('d/m/Y', (time() + (2 * 24 * 60 * 60))) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="width:100%;" align="center">
                        SLIP DE COTIZACIÓN ECOPROTECCION
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 770px; border: 0px solid #FFFF00; text-align:center; margin-top: 8px;">
            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; {{$font_parent}} font-family: Arial;">
                <tr>
                    <td style="width:50%; text-align:right; height: 20px;"><b>Tipo Operacion:</b></td>
                    <td style="width:50%; text-align: left;"> {{ $header->coverage->name }}</td>
                </tr>
            </table>
        </div>
        <br>
        <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
            <h2 style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0; font-weight: bold; {{$font_parent}}">
                Titular 1
            </h2>
            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; {{$font_parent}} font-family: Arial; margin-bottom: 20px;">
                <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                    <td style="width:20%; text-align:center; font-weight:bold;">Apellido Paterno</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Apellido Materno</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Nombres</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Apellido de Casada</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Estado Civil</td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:center;">{{ $header->detail->client->last_name }}</td>
                    <td style="width:20%; text-align:center;">{{ $header->detail->client->mother_last_name }}</td>
                    <td style="width:20%; text-align:center;">{{ $header->detail->client->first_name }}</td>
                    <td style="width:20%; text-align:center;">{{ $header->detail->client->married_name }}</td>
                    <td style="width:20%; text-align:center;">{{ config('base.client_civil_status.' . $header->detail->client->civil_status) }}</td>
                </tr>
                <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                    <td style="width:20%; text-align:center; font-weight:bold;">Sexo</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Tipo de Documento</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Cedula de Identidad</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Fecha de Nacimiento</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">País</td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:center;">
                        {{ empty($header->detail->client->gender) ? '' : config('base.client_genders.' . $header->detail->client->gender) }}
                    </td>
                    <td style="width:20%; text-align:center;">{{ config('base.client_document_types.' . $header->detail->client->document_type) }}</td>
                    <td style="width:20%; text-align:center;">{{ $header->detail->client->dni }} {{ $header->detail->client->extension }}</td>
                    <td style="width:20%; text-align:center;">{{ date('d/m/Y', strtotime($header->detail->client->birthdate)) }}</td>
                    <td style="width:20%; text-align:center;">{{ $header->detail->client->country }}</td>
                </tr>
                <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                    <td style="width:20%; text-align:center; font-weight:bold;">Ciudad</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Barrio/Zona</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Direccion</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Tel. Domicilio</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">Tel. Movil</td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:center; font-weight:bold;">{{ ucwords(str_replace('-', ' ', $header->detail->client->place_residence)) }}</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">{{ $header->detail->client->locality }}</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">{{ $header->detail->client->home_address }}</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">{{ $header->detail->client->phone_number_home }}</td>
                    <td style="width:20%; text-align:center; font-weight:bold;">{{ $header->detail->client->phone_number_mobile }}</td>
                </tr>
            </table>
            <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0;
                    {{$font_parent}}"><b>Tomador:</b> Banco Pyme Ecofuturo S.A.
            </div>
            <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0;
                    {{$font_parent}}"><b>Prima:</b> Bs. 16 mensual ó Bs. 176 anual
            </div>
        </div>
        <br>
        <div style="width: 770px; border: 0px solid #FFFF00; text-align:justify; {{$font_parent}}">
            <table border="0" cellpadding="0" cellspacing="0" style="width:100%;" align="center">
                <tr style="background:#0075AA; color:#FFF;">
                    <td style="width:20%; height:25px; text-align:center;">Coberturas</td>
                    <td style="width:60%; text-align:center;" colspan="3">Suma Asegurada en US$</td>
                    <td style="width:20%; text-align:center;" rowspan="2">Carencia en días</td>
                </tr>
                <tr style="background:#0075AA; color:#FFF;">
                    <td style="width:20%; height:25px; text-align:center;">Descripción</td>
                    <td style="width:20%; text-align:center;">Según Saldo promedio hasta la suma asegurada máxima</td>
                    <td style="width:20%; text-align:center;">Suma Asegurada Máxima</td>
                    <td style="width:20%; text-align:center;">Suma Asegurada Mínima</td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:left; background:#d8e4bc; height:20px; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;"
                        colspan="5">Cobertura principal
                    </td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:left; height:20px; border-top:1px solid #000066; border-left:1px solid #000066;">
                        Muerte natural
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        5 veces el SP
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        30,000
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        400
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;">
                        30
                    </td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:left; height:20px; border-top:1px solid #000066; border-left:1px solid #000066;">
                        Gastos de sepelio
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        N/A
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        200
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        200
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;">
                        30*
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:left; background:#d8e4bc; height:20px; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;"
                        colspan="5">Coberturas adicionales
                    </td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:left; height:20px; border-top:1px solid #000066; border-left:1px solid #000066;">
                        Muerte Accidental
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        10 veces el Saldo Promedio
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        30,000
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        400
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;">
                        No aplica
                    </td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:left; height:20px; border-top:1px solid #000066; border-left:1px solid #000066;">
                        Adelanto por Enfermedad Terminal
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        Equivalente al 25% del beneficio de la cobertura de Muerte Natural
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        No aplica
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        No aplica
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;">
                        30
                    </td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:left; height:20px; border-top:1px solid #000066; border-left:1px solid #000066;">
                        Cobertura indemnizatoria por cáncer
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        No aplica
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        1,000
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        1,000
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;">
                        90
                    </td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:left; height:20px; border-top:1px solid #000066; border-left:1px solid #000066;">
                        Enfermedades graves
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        N/A
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        1,000
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066;">
                        1,000
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066;">
                        90
                    </td>
                </tr>
                <tr>
                    <td style="width:20%; text-align:left; height:20px; border-top:1px solid #000066; border-left:1px solid #000066; border-bottom:1px solid #000066;">
                        <div style="border-bottom:1px solid #000066;">Asistencias adicionales:</div>
                        -Asistencia Legal Telefónica<br/>
                        - Mediphone: Consejo Médico Telefónico<br/>- Servicio Fúnebre Telefónico
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-bottom:1px solid #000066;">
                        Asistencia Legal telefónica: 2 eventos al año
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-bottom:1px solid #000066;">
                        -
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-bottom:1px solid #000066;">
                        -
                    </td>
                    <td style="width:20%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-right:1px solid #000066; border-bottom:1px solid #000066;">
                        -
                    </td>
                </tr>
            </table>
            *No aplica para muerte accidental

            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; {{$font_parent}} font-family: Arial; margin-top: 30px;">
                <tr>
                    <td style="width:20%;"></td>
                    <td style="width:30%;" align="center">{{ $header->detail->client->full_name }}</td>
                    <td style="width:30%;" align="center">{{ date('d/m/Y') }}</td>
                    <td style="width:20%;"></td>
                </tr>
                <tr>
                    <td style="width:20%;"></td>
                    <td style="width:30%;" align="center"><b>Titular 1</b></td>
                    <td style="width:30%;" align="center"><b>Fecha Actual</b></td>
                    <td style="width:20%;"></td>
                </tr>
            </table>

        </div>
    </div>
</div>

@if(isset($sub_module))
    <page>
        <div style="page-break-before: always;">&nbsp;</div>
    </page>
    {!! $sub_module !!}
@endif
