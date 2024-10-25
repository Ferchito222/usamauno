<div style="width: 775px; height: auto; border: 0 none; padding: 10px;">
    <div style="width: 770px; font-weight: normal; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0 none;">
        <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td colspan="3" style="width:100%;" align="center">
                        <img src="{{ asset('images/ecovida.jpg') }}">
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
                        SLIP DE COTIZACIÓN ECOVIDA
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 770px; border: 0px solid #FFFF00; text-align:center; margin-top: 8px;">
            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td style="width:50%; text-align:right; height: 20px;"><b>Tipo Operacion:</b></td>
                    <td style="width:50%; text-align: left;"> {{ $header->coverage->name }}</td>
                </tr>
            </table>
        </div>
        <br>
        <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
            <h2 style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0;
                    font-weight: bold; font-size: 80%;">Titular 1</h2>
            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; font-size: 80%; font-family: Arial; margin-bottom: 20px;">
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
                    font-size: 80%;"><b>Tomador:</b> Banco Pyme Ecofuturo S.A.
            </div>
            <div style="width: auto; height: auto; text-align: left; margin: 7px 0; padding: 0;
                    font-size: 80%;"><b>Prima:</b> Bs. 100 anual
            </div>
        </div>
        <br>
        <div style="width: 770px; border: 0px solid #FFFF00; text-align:justify; font-size: 80%;">
            <table border="0" cellpadding="0" cellspacing="0" style="width:80%; font-size:100%;">
                <tr style="background:#0075AA; color:#FFF;">
                    <td style="width:30%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; height:25px;">
                        Descripción de la cobertura
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #fff; border-top:1px solid #000066;">
                        Suma Asegurada en Bs.
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #fff; border-top:1px solid #000066; border-right:1px solid #000066;">
                        Carencia en días
                    </td>
                </tr>
                <tr>
                    <td style="width:30%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; height:25px;">
                        Muerte natural
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #000066; border-top:1px solid #000066;">
                        7,000.00
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #000066; border-top:1px solid #000066; border-right:1px solid #000066;">
                        30
                    </td>
                </tr>
                <tr>
                    <td style="width:30%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; height:25px;">
                        Muerte accidental
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #000066; border-top:1px solid #000066;">
                        7,000.00
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #000066; border-top:1px solid #000066; border-right:1px solid #000066;">
                        No aplica
                    </td>
                </tr>
                <tr>
                    <td style="width:30%; text-align:center; border-top:1px solid #000066; border-left:1px solid #000066; border-bottom:1px solid #000066; height:25px;">
                        Gastos de sepelio
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #000066; border-top:1px solid #000066; border-bottom:1px solid #000066;">
                        2,000.00
                    </td>
                    <td style="width:25%; text-align:center; border-left:1px solid #000066; border-top:1px solid #000066; border-right:1px solid #000066; border-bottom:1px solid #000066;">
                        30*
                    </td>
                </tr>
            </table>
            *No aplica para muerte accidental

            <table cellpadding="0" cellspacing="0" border="0"
                   style="width: 100%; height: auto; font-size: 80%; font-family: Arial; margin-top: 30px;">
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