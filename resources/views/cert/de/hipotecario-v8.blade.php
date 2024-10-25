@var $copies = 1;

@if($format === 'pdf' && $header->issued)
    @var $copies = $header->copy == 0 ? 1 : $header->copy;
@endif

<div style="width: 710px; font-weight: normal; font-size: 9px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
    @for($c = ($copies == 1 ? 1 : $copies - 2); $c <= $copies; $c++)
        @var $nro_detail=1
        @foreach($header->details as $dk => $detail)
            @if(($header->issued) || (! $header->issued))
                @if($nro_detail>1)
                    <page>
                        <div style="page-break-before: always;">&nbsp;</div>
                    </page>
                @endif
                <header style="text-align: right;">
                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate(getUrlQr(encode($header->retailerProduct->id), $header))) !!} "
                    style="margin-top: -40px; margin-left: 0px;">
                </header>
                <div>
                    <img src="{{ base_path('public/' . $header->retailerProduct->companyProduct->company->image) }}"
                         style="margin-top: -10px; margin-left: 0px; position: fixed;" width="115">
                    <!--
                    <img src="data:image/png;base64,{{ base64_encode($barcode) }}"
                         style="margin-top: -30px; margin-left: 460px; position: fixed;">
                    -->
                    <div style="text-align: right; font-size: 60%; margin-top: 5px;">
                        <span style="font-size: 60%;">
                            @if($header->issued && $copies > 1)
                                @if($c === 0)
                                    ORIGINAL CLIENTE
                                @else
                                    COPIA {{ $c === 1 ? 'BANCO' : ($c === 2 ? 'CIA.' : $c) }}
                                @endif
                                - {{ $header->retailerProduct->retailer->name }}
                            @elseif($header->issued && $copies === 1)
                                ORIGINAL CLIENTE - {{ $header->retailerProduct->retailer->name }}
                            @endif
                        </span>
                        <br>
                        <h3 style="margin: 0; padding: 0;">SOLICITUD DEL SEGURO DE DESGRAVAMEN HIPOTECARIO
                        </h3>

                        <h4 style="font-weight: normal; margin: 0;">
                            Código de Registro 211-934923-2016 06 005 3001
                        </h4>
                    </div>
                    <br />
                    <div style="font-size: 60%; text-align: justify;">
                        Mediante el presente Formulario, en conformidad con la Declaración de Salud que precede, solicito a
                        <strong> la Compañia de Seguros de Vida Fortaleza S.A.</strong>, como Entidad Aseguradora, se me
                        otorgue el Seguro de Desgravamen Hipotecario, con referencia al préstamo que al presente gestiono
                        ante {{ $header->retailerProduct->retailer->name }} de la ciudad  de
                        {{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }} por el plazo
                        de {{ $header->term }} {{ config('base.term_types.' . $header->type_term) }}, con destino a
                        {{ $header->creditProduct->name }}.
                        
                        Se autoriza para los efectos que corresponda declaro y doy mi absoluta conformidad a todas y cada una de las
                        condiciones y estipulaciones establecidas por la Entidad Aseguradora, sobre concesión, vigencia y
                        caducidad del citado seguro, según el Reglamento Aprobado, obligándome a pagar las primas mensuales
                        del seguro solicitado.
                        <br>
                        <span style="font-size: 60%;">
                            Yo {{ $detail->client->full_name }}
                            @if($detail->client->gender === 'F' && ! empty($detail->client->married_name))
                                @if($detail->client->civil_status === 'C')
                                    {{ $detail->client->married_name }}
                                @elseif($detail->client->civil_status === 'V')
                                    vda. de {{ $detail->client->married_name }}
                                @endif
                            @endif

                            <br>
                            Lugar y Fecha:
                            {{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }}
                            {{ is_null($header->date_issue) ? '' : '- ' . $header->date_issue->format('d/m/Y') }}
                        </span>
                    </div>

                    <div style="text-align: right; font-size: 60%;">
                        <h3 style="margin: 0;">DECLARACION JURADA DE SALUD PARA SEGURO DE DESGRAVAMEN HIPOTECARIO</h3>
                        <h4 style="font-weight: normal; margin: 0;">
                            Código de Registro 211-934923-2016 06 005 3002
                        </h4>
                    </div>

                    <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 60%;">
                        <tr>
                            <td style="width: 16%;">Nombre Completo: </td>
                            <td style="width: 84%; border-bottom: 1px dotted #000000;">
                                {{ $detail->client->full_name }}
                                @if($detail->client->gender === 'F' && ! empty($detail->client->married_name))
                                    @if($detail->client->civil_status === 'C')
                                        {{ $detail->client->married_name }}
                                    @elseif($detail->client->civil_status === 'V')
                                        vda. de {{ $detail->client->married_name }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 60%;">
                        <tr>
                            <td style="width: 16%;">Carnet de Identidad: </td>
                            <td style="width: 74%; border-bottom: 1px dotted #000000;">
                                {{ $detail->client->dni . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }} {{  $detail->client->extension=='SE'?'':$detail->client->extension }}
                            </td>
                            <td style="width: 10%;"> </td>
                        </tr>
                    </table>
                    <div>
                        Entidad de Intermediación Financiera: {{ $header->retailerProduct->retailer->name }}
                    </div>
                    <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 60%;">
                        <tr>
                            <td style="width: 20%;">Monto del Préstamo: </td>
                            <td style="width: 38%; border-bottom: 1px dotted #000000;">
                                {{ number_format($header->amount_requested, 2, '.', ',') }} {{ $header->currency }}
                            </td>
                            <td style="width: 20%;">Fecha de Nacimiento: </td>
                            <td style="width: 12%; border-bottom: 1px dotted #000000; text-align: center;">
                                {{ $detail->client->birthdate->format('d/m/Y') }}
                            </td>
                            <td style="width: 10%;"> </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">Ocupación Principal</td>
                            <td colspan="4" style="width: 80%; border-bottom: 1px dotted #000000;">
                                {{ $detail->client->activity->occupation }} /
                                {{ $detail->client->occupation_description }} - {{ $detail->client->workplace }}
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" style="width: 40%; font-size: 60%;">
                        <tr>
                            <td style="width: 25%;">Estatura(cm): </td>
                            <td style="width: 25%; border-bottom: 1px dotted #000000; text-align: center;">
                                {{ $detail->client->height }}.
                            </td>
                            <td style="width: 20%;">Peso(Kg): </td>
                            <td style="width: 30%; border-bottom: 1px dotted #000000; text-align: center;">
                                {{ $detail->client->weight }}.
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" style="width: 40%; font-size: 60%;">
                        <tr>
                            <td style="width: 30%;">Plazo: </td>
                            <td style="width: 70%; border-bottom: 1px dotted #000000; text-align: center;">
                                {{ $header->term }} {{ config('base.term_types.'.$header->type_term) }}
                            </td>
                        </tr>
                    </table>
                    <br>

                    <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 60%;">
                        @var $responses = json_decode($detail->response->response, true);

                        @foreach($responses as $key => $response)
                            <tr>
                                <td style="width: 5%; text-align: right;" valign="top">{{ $key }})</td>
                                <td style="width: 65%; padding-left: 10px; {{ $response['response_text']  ? 'border-bottom: 1px dotted #000000;' : '' }}">
                                    {{ $response['question'] }}
                                    @if($response['response_text'])
                                        <br>
                                        En caso que la respuesta sea afirmativa, favor especificar:
                                        <br>
                                        @if(key_exists('response_specification', $response))
                                            @if(! empty($response['response_specification']))
                                                {{ $response['response_specification'] }}
                                            @else
                                                <br>
                                            @endif
                                        @else
                                            <br>
                                        @endif
                                    @else
                                        <br><br><br>
                                    @endif
                                </td>
                                <td style="width: 10%;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                        <tr>
                                            <td style="width: 40%;" valign="top">SI </td>
                                            <td style="width: 40%; height: 13px; border: 1px solid #000000; text-align: center; padding-bottom: 12px;">
                                                {{ $response['response'] ? 'X' : '' }}
                                            </td>
                                            <td style="width: 20%;"></td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 10%;">
                                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                        <tr>
                                            <td style="width: 40%;" valign="top">NO</td>
                                            <td style="width: 40%; height: 13px; border: 1px solid #000000; text-align: center; padding-bottom: 12px;">
                                                {{ ! $response['response'] ? 'X' : '' }}
                                            </td>
                                            <td style="width: 20%;"></td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="width: 10%;"> </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                    <tr>
                        <td style="width: 20%;">Beneficiario para Cobertura de Sepelio(Si fuere Contratado):</td>
                        <td style="width: 20%; text-align: center;">
                            @if(!is_null($detail->beneficiary))
                                {{$detail->beneficiary->first_name}}
                            @endif
                        </td>
                        <td style="width: 20%; text-align: center;">
                            @if(!is_null($detail->beneficiary))
                                {{$detail->beneficiary->last_name}}
                            @endif
                        </td>
                        <td style="width: 20%; text-align: center;">
                            @if(!is_null($detail->beneficiary))
                                {{$detail->beneficiary->mother_last_name}}
                            @endif
                        </td>
                        <td style="width: 20%; text-align: center;">
                            @if(!is_null($detail->beneficiary))
                                {{$detail->beneficiary->relationship}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%; border-top: 1px dotted #000000; text-align: center;">
                            Nombres
                        </td>
                        <td style="width: 20%; border-top: 1px dotted #000000; text-align: center;">
                            Apellido Paterno
                        </td>
                        <td style="width: 20%; border-top: 1px dotted #000000; text-align: center;">
                            Apellido Materno
                        </td>
                        <td style="width: 20%; border-top: 1px dotted #000000; text-align: center;">
                            Relaci&oacute;n/Parentesco
                        </td>
                    </tr>
                </table>
            
                <div style="margin-top: 10px; display: block; width: 100%; font-size: 60%; text-align: justify;">
                    Declaro que las respuestas que he consignado en este Formulario de Solicitud de Seguro de Desgravamen
                    Hipotecario y Declaración de Salud son verdaderas y completas.
                    <br><br>
                    Autorizo, a los médicos, clínicas, hospitales y otros centros de salud que me hayan atendido para que
                    proporcionen a la Entidad Aseguradora, todos los resultados de los informes referentes a mi salud, en
                    caso de enfermedad o accidentes, para lo cual libero a dichos médicos y centros médicos, en relación con
                    su secreto profesional, de toda responsabilidad en que pudiera incurrir al proporcionar tales informes.
                </div>
                <br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 80%; font-size: 60%;">
                    <tr>
                        <td style="width:18%;">Solicitante Nombre: </td>
                        <td style="width:42%; border-bottom:1px solid black;text-align: center">
                            {{ $detail->client->full_name }}
                            @if($detail->client->gender === 'F' && ! empty($detail->client->married_name))
                                @if($detail->client->civil_status === 'C')
                                    {{ $detail->client->married_name }}
                                @elseif($detail->client->civil_status === 'V')
                                    vda. de {{ $detail->client->married_name }}
                                @endif
                            @endif
                        </td>
                        <td style="width:7%;"></td>
                        <td style="width:5%;">Firma: </td>
                        <td style="width:28%; border-bottom:1px solid black;text-align: center"></td>
                    </tr>
                </table>
                <br />
                <br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 70%; font-size: 60%;">
                    <tr>
                        <td style="width:30%;">Oficial de Crédito (Firma y Sello): </td>
                        <td style="width:70%; border-bottom:1px solid black;text-align: center"></td>
                    </tr>
                </table>
                <br />
                <div style="font-style: oblique; text-align: center;">
                    <strong>Esta Declaración de Salud tiene validez de 180 días</strong><br />
                    Las solicitudes que en cumulo superen los $us. 500.000, tienen una validez de 90 días calendarios a partir del 
                    pronunciamiento de la Compañía Aseguradora para el desembolso del crédito.
                    <br />
                    <table style="width: 100%;text-align: center;">
                        <tr>
                            <td style="width: 25%;">&nbsp;</td>
                            <td style="width: 50%; border: 1px solid #8a8a8a;">
                                Cúmulo Cliente: {{$header->currency=='USD'?'$us':'Bs'}} 
                                {{ mountByCurrency($header->currency, $detail->cumulus, $header->retailerProduct->retailer->exchangeRate) }}
                            </td>
                            <td style="width: 25%;">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                @if($header->facultative && ! is_null($detail->facultative))
                    @if($detail->facultative->state === 'PR')
                        <table border="0" cellpadding="1" cellspacing="0"
                               style="width: 100%; font-size: 8px; font-weight: normal; font-family: Arial; margin: 5px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">
                            <tr>
                                <td colspan="9" style="border: 1px solid #8a8a8a; width:100%; text-align: center; font-weight: bold;">
                                    Caso Facultativo
                                </td>
                            </tr>
                            <tr>
                                <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Aprobado
                                </td>
                                <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Tasa de Recargo
                                </td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Porcentaje de Recargo
                                </td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Tasa Actual
                                </td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Tasa Recargo
                                </td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Tasa Desempleo
                                </td>
                                <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Tasa Total
                                </td>
                                <td style="width:27.5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Respuestas en el Cetificado de Desgravamen
                                </td>
                                <td style="width:27.5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Observaciones
                                </td>
                            </tr>
                            <tr>
                                <td style="width:5%; text-align: center; border: 1px solid #8a8a8a;">
                                    {{ $detail->facultative->approved ? 'SI' : 'NO' }}
                                </td>
                                <td style="width:5%; text-align: center; border: 1px solid #8a8a8a;">
                                    {{ $detail->facultative->surcharge ? 'SI' : 'NO' }}
                                </td>
                                <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                                    {{ $detail->facultative->percentage }}
                                </td>
                                <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                                    {{ $detail->facultative->current_rate }} %
                                </td>
                                <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                                    {{ $detail->facultative->final_rate }} %
                                </td>
                                <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                                    @if($detail->cesantia == true)
                                        {{ $detail->rate_cesantia }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                                <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                                    {{$detail->facultative->current_rate+$detail->facultative->final_rate+$detail->rate_cesantia}}%
                                </td>
                                <td style="width:27.5%; text-align: justify; border: 1px solid #8a8a8a;">
                                    <span>{{ $detail->facultative->observation }}</span>
                                </td>
                                <td style="width:27.5%; text-align: justify; border: 1px solid #8a8a8a;">
                                    <span>{{ $header->facultative_observation }}</span>
                                </td>
                            </tr>
                        </table>
                    @else
                        <table border="0" cellpadding="1" cellspacing="0"
                               style="width: 100%; font-size: 9px; border-collapse: collapse; font-weight: normal; font-family: Arial; margin: 2px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">
                            <tr>
                                <td style="border: 1px solid #8a8a8a; text-align: center; font-weight: bold;">
                                    Caso Facultativo
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                                    Observaciones
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: justify; border: 1px solid #8a8a8a;">
                                    {{ $header->facultative_observation }}
                                </td>
                            </tr>
                        </table>
                    @endif
                @else
                <table border="0" cellpadding="1" cellspacing="0"
                        style="width: 100%; font-size: 8px; font-weight: normal; font-family: Arial; margin: 5px 0 0 0; padding: 0; border-collapse: collapse; vertical-align: bottom;">
                     <tr>
                         <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                             Aprobado
                         </td>
                         <td style="width:5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                             Tasa de Recargo
                         </td>
                         <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                             Porcentaje de Recargo
                         </td>
                         <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                             Tasa Actual
                         </td>
                         <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                             Tasa Recargo
                         </td>
                         <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                            Tasa Desempleo
                        </td>
                        <td style="width:7%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                            Tasa Total
                        </td>
                         <td style="width:27.5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                             Respuestas en el Cetificado de Desgravamen
                         </td>
                         <td style="width:27.5%; text-align: center; font-weight: bold; border: 1px solid #8a8a8a;">
                             Observaciones
                         </td>
                     </tr>
                     <tr>
                         <td style="width:5%; text-align: center; border: 1px solid #8a8a8a;">
                             SI
                         </td>
                         <td style="width:5%; text-align: center; border: 1px solid #8a8a8a;">
                             NO
                         </td>
                         <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                             0%
                         </td>
                         <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                             {{ $detail->rate }} %
                         </td>
                         <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                             0%
                         </td>
                         <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                            @if($detail->cesantia == true)
                                {{ $detail->rate_cesantia }}%
                            @else
                                0%
                            @endif
                         </td>
                         <td style="width:7%; text-align: center; border: 1px solid #8a8a8a;">
                             {{$detail->rate+$detail->rate_cesantia}}%
                         </td>
                         <td style="width:27.5%; text-align: justify; border: 1px solid #8a8a8a;">

                         </td>
                         <td style="width:27.5%; text-align: justify; border: 1px solid #8a8a8a;">

                         </td>
                     </tr>
                 </table>
                @endif

                @if($detail->approved && $header->issued && ! $header->canceled)
                    <page>
                        <div style="page-break-before: always;">&nbsp;</div>
                    </page>
                    <header style="text-align: right;">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate(getUrlQr(encode($header->retailerProduct->id), $header))) !!} "
                        style="margin-top: -40px; margin-left: 0px;">
                    </header>
                    <div style="text-align: center;">
                        <strong>CERTIFICADO DE COBERTURA INDIVIDUAL</strong><br />
                        <strong>SEGURO DE DESGRAVAMEN HIPOTECARIO</strong><br />
                        <br />
                        Formato aprobado mediante Resolución Administrativa APS/DS/Nº 822-2016 del 22 de junio de 2016<br />
                        CODIGO DE REGISTRO: 211-934923-2016 06 005 4001
                    </div>
                    <br />
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 50%">
                                <strong>
                                    PÓLIZA MADRE Nro. : {{ $header->policy_number }}
                                </strong>
                            </td>
                            <td style="width: 50%">
                                <strong>
                                    CERTIFICADO Nro.: <!--DES-{{$header->user->city->abbreviation}}- -->{{$header->issue_number}}{{$header->version_number}}
                                </strong>
                            </td>
                        </tr>
                    </table>
                    <br />
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 30%">
                                <strong>TOMADOR:</strong>
                            </td>
                            <td style="width: 70%">
                                <strong>{{config('base.tomador.'.$header->user->city->abbreviation.'.tomador')}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>ASEGURADO:</strong>
                            </td>
                            <td style="width: 70%">
                                <strong>{{ $detail->client->full_name }}
                                @if($detail->client->gender === 'F' && ! empty($detail->client->married_name))
                                            @if($detail->client->civil_status === 'C')
                                                {{ $detail->client->married_name }}
                                            @elseif($detail->client->civil_status === 'V')
                                                vda. de {{ $detail->client->married_name }}
                                            @endif
                                        @endif</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>BENEFICIARIOS:</strong>
                            </td>
                            <td style="width: 70%">
                                ENTIDAD DE INTERMEDIACIÓN FINANCIERA
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>BENEFICIARIOS DE COBERTURAS ADICIONALES:</strong>
                            </td>
                            <td style="width: 70%">
                                @if(!is_null($detail->beneficiary))
                                    {{$detail->beneficiary->full_name}} {{$detail->beneficiary->dni}} {{$detail->beneficiary->extension=='SE'?'':$detail->beneficiary->extension}} - {{$detail->beneficiary->relationship}}
                                @endif
                            </td>
                        </tr>
                    </table>
                    <br />
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 40%">
                                <strong>VIGENCIA DE LA PÓLIZA PRINCIPAL:</strong>
                            </td>
                            <td style="width: 60%">
                                <strong>
                                    Desde las 00:01 Hrs. del día 01 de Abril de 2020
                                </strong>
                            </td>
                            
                        </tr>
                        <tr>
                            <td style="width: 40%">
                            </td>
                            <td style="width: 60%">
                                <strong>
                                    Hasta las 24:00 Hrs. del día 31 de Marzo de 2023
                                </strong>
                            </td>
                            
                        </tr>
                    </table>
                    <br />
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 30%">
                                <strong>CIUDAD:</strong>
                            </td>
                            <td style="width: 70%">
                                {{ $header->user->city->name}}
                            </td>
                        </tr>
                    </table>
                    <br />
                    <strong><u>INFORMACIÓN DE LA ASEGURADORA</u></strong>
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 30%">
                                <strong>RAZÓN SOCIAL:</strong>
                            </td>
                            <td style="width: 70%">
                                COMPAÑÍA DE SEGUROS DE VIDA FORTALEZA S.A
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>DIRECCIÓN:</strong>
                            </td>
                            <td style="width: 70%">
                                AV. VIRGEN DE COTOCA NRO. 2080
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>TELÉFONO:</strong>
                            </td>
                            <td style="width: 70%">
                                3 - 3487273
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>FAX:</strong>
                            </td>
                            <td style="width: 70%">
                                3 - 3497675 interno 100
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>E-MAIL:</strong>
                            </td>
                            <td style="width: 70%">
                                info@fortaleza.com.bo
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 30%">
                                <strong>PÁGINA WEB:</strong>
                            </td>
                            <td style="width: 70%">
                                www.aseguradorafortaleza.com.bo
                            </td>
                        </tr>
                    </table>
                    <br />
                    EL PRESENTE CERTIFICADO DE COBERTURA INDIVIDUAL TIENE VALIDEZ LEGAL PARA TODA ENTIDAD ASEGURADORA QUE OPERA EN LA
                    MODALIDAD DE SEGUROS DE PERSONAS Y QUE OTORGA EL SEGURO DE DESGRAVAMEN HIPOTECARIO PARA LO CUAL EL ASEGURADO
                    EXPRESA DE MANERA VOLUNTARIA SU ADHESIÓN AL PRESENTE SEGURO.<br />
                    <br />
                    <strong>VIGENCIA DE LA COBERTURA INDIVIDUAL DEL ASEGURADO: </strong> La vigencia individual de la cobertura para cada Asegurado será mensual renovable
                    automáticamente, iniciándose en el momento del desembolso del Préstamo por parte de la Entidad de Intermediación Financiera a favor del Asegurado
                    (Prestatario) y finalizando en el momento de la extinción de la operación de préstamo. Esta vigencia se interrumpirá en caso de incumplimiento de pago de la
                    prima correspondiente, treinta días después de la fecha de vencimiento de pago.<br />
                    <br />
                    Los reemplazos de la Entidad Aseguradora que se dieran durante el periodo de vigencia del préstamo, no interrumpirán la vigencia de la Cobertura
                    Individual.<br />
                    <br />
                    <strong>CAPITAL ASEGURADO: </strong> El Capital Asegurado durante la vigencia de la Póliza corresponderá, para la cobertura de Fallecimiento o Invalidez Total y
                    Permanente de la Póliza de Seguro de Desgravamen Hipotecario, al Valor del Saldo Insoluto de la Deuda; y para las Coberturas Adicionales corresponderá
                    al valor establecido en presente Certificado.<br />
                    <br />
                    <strong>PRIMA: </strong> El monto de la Prima de Tarifa del Seguro de Desgravamen Hipotecario se determinará aplicando la tasa neta al Capital Asegurado.<br />
                    <br />
                    <strong>COBERTURAS:</strong><br />
                    <strong>COBERTURAS BÁSICAS </strong> (considerando las exclusiones de la póliza)<br />
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 40%">
                                - FALLECIMIENTO POR CUALQUIER CAUSA
                            </td>
                            <td style="width: 30%; text-align: center">
                                Bs
                            </td>
                            <td style="width: 30%; text-align: right">
                                @if($header->currency=='USD')
                                    {{ number_format(($header->amount_requested*$header->retailerProduct->retailer->exchangeRate->bs_value), 2, '.', ',') }}
                                @else
                                    {{ number_format($header->amount_requested, 2, '.', ',') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%">
                                - INVALIDEZ TOTAL Y PERMANENTE
                            </td>
                            <td style="width: 30%; text-align: center">Bs</td>
                            <td style="width: 30%; text-align: right">
                                @if($detail->coverages->count() >0 && $detail->coverages->where("coverage","CTPD")->first()->granted == true)
                                    @if($header->currency=='USD')
                                        {{ number_format(($header->amount_requested*$header->retailerProduct->retailer->exchangeRate->bs_value), 2, '.', ',') }}
                                    @else
                                        {{ number_format($header->amount_requested, 2, '.', ',') }}
                                    @endif
                                @else
                                    0.00
                                @endif

                            </td>
                        </tr>
                    </table>
                    <br />
                    COBERTURAS ADICIONALES:<br />
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 40%">
                                - Gastos de Sepelio.
                            </td>
                            <td style="width: 30%; text-align: center">
                                Bs
                            </td>
                            <td style="width: 30%; text-align: right">
                                3,430.00
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 40%">
                                - Capital Adicional Indemnizatorio.
                            </td>
                            <td style="width: 60%; text-align: right" colspan="2">
                                @if($detail->cesantia == true)
                                    Hasta seis (6) meses de cuotas mensuales
                                @else
                                    No corresponde
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    <br /><br />
                    PERMANENCIA DEL SEGURO:<br />
                    Fallecimiento: Hasta cumplir los 75 años y 364 días.<br />
                    Invalidez Total y Permanente: Hasta cumplir los 70 años y 364 días.
                    <br /><br />
                    LUGAR y FECHA:
                        {{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }}
                        {{ is_null($header->date_issue) ? '' : '- ' . $header->date_issue->format('d/m/Y') }}
                    <br />
                    <div style="text-align: center;">
                        <strong>COMPAÑÍA DE SEGUROS DE VIDA FORTALEZA S.A.</strong>
                    </div>
                    <br />
                    <table border="0" cellpadding="1" cellspacing="0"
                            style="width: 100%; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                        <tr>
                            <td style="width: 10%">
                            </td>
                            <td style="width: 30%; text-align: center;">
                                <img src="{{ base_path('public/images/vanessa_soria_20200630.jpg') }}" width="100">
                            </td>
                            <td style="width: 20%">
                            </td>
                            <td style="width: 30%; text-align: center;">
                                <img src="{{ base_path('public/images/max_rodrigo_20211117.jpg') }}" width="100">
                            </td>
                            <td style="width: 10%">
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 10%">
                            </td>
                            <td style="width: 30%; text-align: center;border-top: solid;">
                                <strong>
                                    Gerente Técnico<br />
                                        Vanessa Soria Monasterio<br />
                                </strong>
                            </td>
                            <td style="width: 20%">
                            </td>
                            <td style="width: 30%; text-align: center;border-top: solid;">
                                <strong>
                                    Jefe Técnico Banca Seguros<br />
                                    Max Rodrigo Garcia Arteaga<br />
                                </strong>
                            </td>
                            <td style="width: 10%">
                            </td>
                        </tr>
                    </table>
                    <page>
                        <div style="page-break-before: always;">&nbsp;</div>
                    </page>
                    <header style="text-align: right;">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate(getUrlQr(encode($header->retailerProduct->id), $header))) !!} "
                        style="margin-top: -40px; margin-left: 0px;">
                    </header>
                    <div style="margin: 2mm 1mm 2mm 2mm;text-align: justify;font-size: 7px; font-family: Arial, Helvetica, sans-serif;">
                        <div style="text-align: center; font-weight: bold;">
                            CERTIFICADO DE COBERTURA INDIVIDUAL SEGURO DE DESGRAVAMEN HIPOTECARIO<br />
                            Formato aprobado mediante Resolución Administrativa APS/DS/Nº 822-2016 del 22 de junio de 2016 <br />
                            CODIGO DE REGISTRO: 211-934923-2016 06 005 4001<br />

                            CONDICIONES DE LA PÓLIZA DE SEGURO DE DESGRAVAMEN HIPOTECARIO
                        </div>
                        <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 45%;font-family: Arial, Helvetica, sans-serif;">
                            <tr>
                                <td style="width: 48%;text-align: justify;">
                                    <strong>COBERTURA DEL SEGURO:</strong> El Capital Asegurado será pagado por la Entidad Aseguradora, 
                                    cuando ocurra uno de los siguientes eventos:
                                    <br />
                                    <dl>
                                        <dd>
                                            <strong>Fallecimiento:</strong> La muerte por cualquier causa del Asegurado, 
                                            si ésta ocurriera durante la vigencia de la Póliza y la causa no se 
                                            encuentre expresamente excluida.<br />
                                            <strong>Invalidez Total y Permanente:</strong> Cuando la situación física del 
                                            Asegurado como consecuencia de una enfermedad o accidente presenta una pérdida o 
                                            disminución de su capacidad física y/o intelectual, igual o superior al 60% de 
                                            su capacidad de trabajo, siempre que el grado de tal incapacidad sea reconocido 
                                            y formalizado por el Instituto Nacional de Salud Ocupacional (INSO) o la Entidad 
                                            Encargada de Calificar (EEC) o por un médico calificador debidamente registrado en la APS.
                                        </dd>
                                    </dl>
                                    <br />
                                    <strong>EXCLUSIONES DE COBERTURA:</strong> La Entidad Aseguradora no cubrirá y estará eximida 
                                    de toda responsabilidad, en caso que el Fallecimiento o Invalidez Total y Permanente del 
                                    Asegurado sobrevenga, directa o indirectamente, como consecuencia de:
                                    <br />
                                    <ul type="a">
                                        <li>Enfermedad pre-existente que no fue comunicada por el Asegurado a través del 
                                            Formulario de Solicitud de Seguro y Declaración de Salud.</li>
                                        <li>Intervención directa o indirecta del Asegurado en actos criminales, que le ocasionen 
                                            el Fallecimiento o Invalidez Total y Permanente.</li>
                                        <li>Guerra internacional o civil (declarada o no), revolución, invasión, actos 
                                            de enemigos extranjeros, hostilidades u operaciones bélicas, insurrección, 
                                            sublevación, rebelión, sedición, motín o hechos que las leyes califican como 
                                            delitos contra la seguridad del Estado.</li>
                                        <li>Fisión, fusión nuclear o contaminación radioactiva.</li>
                                        <li>Realización o participación en una actividad o deporte riesgoso no declarada por 
                                            el Asegurado a través del Formulario de Solicitud de Seguro y Declaración de Salud, 
                                            considerándose como tales aquellos que objetivamente constituyan una agravación del 
                                            riesgo o se requiera de medidas de protección o seguridad para realizarlos.</li>
                                        <li>Suicidio causado dentro de los dos primeros años a partir del desembolso del préstamo.</li>
                                    </ul>
                                    <br />
                                    <br />
                                    <strong>OBLIGACIÓN DE DECLARAR DEL ASEGURADO:</strong> El Asegurado está obligado a declarar 
                                    objetiva y verazmente las afectaciones de salud que tiene y todo hecho y circunstancias que 
                                    tengan importancia para la determinación del estado de riesgo, tal como lo conozca; a través 
                                    del Formulario de Declaración de Salud proporcionado por la Entidad Aseguradora.
                                    <br />
                                    Si se extendió la póliza de Seguro de Desgravamen Hipotecario sin exigir al Asegurado las 
                                    declaraciones escritas, se presume que la Entidad Aseguradora conocía el estado de riesgo, 
                                    salvo que ésta pruebe dolo o mala fe del Asegurado.
                                    <br />
                                    <br />
                                    <strong>RETICENCIA O INEXACTITUD:</strong> La reticencia o inexactitud en las declaraciones 
                                    del Asegurado en el Formulario de Declaración de Salud hacen anulable el Certificado de 
                                    Cobertura, siempre y cuando dicha reticencia o inexactitud suponga ocultación de antecedentes, 
                                    de tal importancia que, de ser conocidos por la Entidad Aseguradora, ésta no habría otorgado 
                                    la o las coberturas del contrato o de hacerlo, lo hubiera hecho en condiciones distintas. 
                                    La Entidad Aseguradora deberá demostrar este aspecto al momento de alegar reticencia o inexactitud.
                                    <br />
                                    <br />
                                    Las declaraciones falsas o reticentes hechas con dolo o mala fe por parte del Asegurado 
                                    hacen nula la Cobertura Individual, en tal caso el Asegurado no tendrá derecho a la 
                                    devolución de las primas pagadas.
                                    <br />
                                    <br />
                                    Se presume la buena fe del Asegurado, correspondiendo probar lo contrario a la Entidad Aseguradora.
                                    <br />
                                    La Entidad Aseguradora no puede alegar reticencia o inexactitud, en los siguientes casos:
                                    <br />
                                    <ul type="a">
                                        <li>Si la reticencia o inexactitud no implica un mayor riesgo, tal que conocidos por la Entidad Aseguradora los hechos o estados de situación verdaderos, la misma admitiría el riesgo sin recargo alguno.</li>
                                        <li>Si la Entidad Aseguradora otorga cobertura al Asegurado con el Certificado de Cobertura Individual sin exigir la Declaración de Salud.</li>
                                        <li>Si el Asegurado al momento de su Declaración de Salud no conocía el estado del riesgo.</li>
                                        <li>Si la Entidad Aseguradora no pidió antes de la emisión del Certificado de Cobertura Individual, las aclaraciones en puntos manifiestamente vagos y/o imprecisos de las declaraciones.</li>
                                        <li>Si la Entidad Aseguradora por otros medios de manera previa a la aceptación del estado del riesgo tuvo conocimiento del verdadero estado del nesgo.</li>
                                        <li>Si la reticencia o inexactitud no tiene relación con la producción del siniestro o sus efectos.</li>
                                    </ul>
                                    <br />
                                    <strong>INDISPUTABILIDAD:</strong> La validez de esta Póliza y su Cobertura no será discutida después 
                                    de transcurridos los dos años desde el momento de la fecha de desembolso del préstamo y de la 
                                    aceptación expresa o tácita de la Entidad Aseguradora.
                                    Si dentro de los dos años desde la fecha de desembolso del préstamo, la Entidad Aseguradora no 
                                    ha pretendido impugnar o anular dicha cobertura por reticencia o inexactitud en las Declaraciones de 
                                    Salud del Asegurado. La Entidad Aseguradora pasado dicho plazo, está impedida de pretender la impugnación o anulación.
                                    Para efectos del cómputo del plazo mencionado precedentemente, se considerará la permanencia 
                                    continua e ininterrumpida de la Cobertura Individual, no obstante, la misma hubiera sido 
                                    otorgada por más de una Entidad Aseguradora.
                                    La falta de pago de primas por parte del Asegurado libera a la Entidad Aseguradora a Indemnizar en 
                                    caso de producido el evento.
                                    <br />
                                    <br />
                                    <strong>SUICIDIO:</strong> La Entidad Aseguradora no se libera de pagar el siniestro correspondiente 
                                    en caso de producirse el suicidio del Asegurado, después de dos (2) años desde el desembolso del préstamo.
                                    <br />
                                    <br />
                                    <strong>PRIMA Y REHABILITACIÓN:</strong> La Prima de Tarifa a ser pagada por el Asegurado, 
                                    resultará del producto de una tasa neta mensual aplicable al Capital Asegurado.
                                    <br />
                                    La prima es debida desde el momento de la celebración del contrato, pero no es exigible 
                                    sino con la emisión del presente Certificado de Cobertura Individual. 
                                    <br /><br /><br />
                                </td>
                                <td style="width: 4%;"></td>
                                <td style="width: 48%;text-align: justify;">
                                    El pago de la prima deberá ser efectuado mensualmente por el Asegurado a la Entidad Aseguradora, a través de la Entidad de Intermediación Financiera, designada por la Entidad Aseguradora, en las mismas fechas del cronograma de amortización del préstamo, salvo que en el Condicionado Particular de la Póliza se establezca una modalidad diferente. No incurre en mora el Asegurado, si el lugar del pago o el domicilio de la Entidad Aseguradora o el lugar indicado en la Póliza han sido cambiados sin su conocimiento.
                                    <br />
                                    <br />
                                    El incumplimiento de pago de la prima treinta (30) días después de la fecha en que debió efectuarse, interrumpirá la vigencia de la Cobertura Individual del Asegurado.
                                    <br />
                                    <br />
                                    El Asegurado o el Tomador del seguro puede, en cualquier momento, rehabilitar la Cobertura, con el pago de la(s) prima(s) atrasada(s) y los intereses devengados sin la necesidad de examen médico.
                                    <br />
                                    <br />
                                    El abono de las primas de la Entidad de Intermediación Financiera a la Entidad Aseguradora, en forma posterior a la fecha en que el Asegurado pagó la prima, no significará mora o incumplimiento atribuible al Asegurado, y cualquier contingencia o perjuicio que causen dichas situaciones al Asegurado, serán de responsabilidad plena de la Entidad de Intermediación Financiera.
                                    <br />
                                    <br />
                                    <strong>PROCEDIMIENTO EN CASO DE SINIESTRO:</strong> El Asegurado o Beneficiario, en un plazo máximo de quince (15) días calendario de tener conocimiento del siniestro, deberá comunicar tal hecho a la Entidad Aseguradora, salvo fuerza mayor o impedimento justificado.
                                    <br />
                                    <br />
                                    La Entidad Aseguradora debe pronunciarse sobre el derecho del Asegurado o Beneficiario dentro de los (30) días de recibida la información y evidencias del Siniestro. Se dejará constancia escrita de la fecha de recepción de la información y evidencias a efecto del cómputo de plazos.
                                    <br />
                                    <br />
                                    El plazo de (30) días mencionado, fenece con la aceptación o rechazo del Siniestro con la solicitud de la Entidad Aseguradora al Asegurado para que se complemente la información, y este plazo no vuelve a correr hasta que el Asegurado haya cumplido con tales requerimientos.
                                    <br />
                                    <br />
                                    La solicitud de complementación por parte de la Entidad Aseguradora no podrá extenderse por más de dos veces a partir de la primera solicitud de informes y evidencias, debiendo pronunciarse dentro del plazo establecido y de manera definitiva sobre el derecho del Asegurado y/oo Beneficiario, después de la entrega por parte del Asegurado y/o Beneficiario del último requerimiento de información.
                                    <br/>
                                    <br/>
                                    El silencio de la Entidad Aseguradora, vencido el término para pronunciarse o vencida(s) la(s) solicitud(es) de complementación, importa la aceptación del reclamo
                                    <br />
                                    <ul type="a">
                                        <li>
                                            <strong>Documentación para el pago de indemnización en caso de Fallecimiento del Asegurado</strong>
                                            <br />
                                            -	Certificado de Defunción extendido por Oficial de Registro Civil. Si el Asegurado hubiera fallecido fuera del país, el indicado Certificado deberá llevar las legalizaciones correspondientes del consulado boliviano del país donde hubiera ocurrido el hecho o el consulado boliviano más accesible, y el de la Autoridad Competente en territorio del Estado Plurinacional de Bolivia.
                                            <br />
                                            En caso de que la obtención del Certificado de Defunción fuera dificultosa por ausencia de Oficinas de Registro Civil en la jurisdicción municipal del domicilio del Asegurado siniestrado o en la jurisdicción municipal colindante del municipio donde vive el Asegurado siniestrado, podrá ser aceptada una certificación extendida por la autoridad comunitaria competente del lugar de ocurrencia del siniestro, con la participación de dos personas en calidad de testigos.
                                            <br />
                                            -	Documento de identificación del Asegurado.
                                            <br />
                                            -	Formulario de declaración de siniestro o nota de denuncia del siniestro.
                                            <br />
                                            -	Documento de Pre-liquidación del préstamo emitido por el Tomador.
                                        </li>
                                        <li>
                                            <strong>Documentación para el Pago de la indemnización en caso de Invalidez Total y Permanente</strong>
                                            <br />
                                            -	Declaración Médica de Invalidez, emitida por el Instituto Nacional de Salud Ocupacional (INSO) o por la Entidad Encargada de Calificación (EEC) o por el médico calificador registrado en la APS.
                                            <br />
                                            -	Documento de identificación del Asegurado.
                                            <br />
                                            -	Formulario de declaración de siniestro o nota de denuncia del siniestro.
                                            <br />
                                            -	Documento de Pre-liquidación del préstamo emitido por el Tomador.
                                        </li>
                                    </ul>
                                    <br />
                                    <strong>PERDIDA DEL DERECHO A LA INDEMNIZACIÓN:</strong> El Asegurado o el Beneficiario pierde el derecho a 
                                    la indemnización o pago de prestaciones convenidas, cuando:
                                    <br />
                                    <ul type="a">
                                        <li>Provoque dolosamente el siniestro;</li>
                                        <li>Oculte o altere, maliciosamente, en la verificación del siniestro, los hechos y circunstancias relacionados	al aviso del siniestro y la documentación requerida por la Entidad Aseguradora.</li>
                                        <li>Recurra a pruebas falsas con el ánimo de obtener un beneficio ilícito.</li>
                                    </ul>
                                    <br />
                                    <strong>CONTROVERSIAS:</strong> Las controversias de hecho sobre las características técnicas del seguro, serán resueltas a través del peritaje, de acuerdo a lo establecido en la Póliza de Seguro y el presente Certificado. Si por esta vía no se llegara a un acuerdo sobre dichas controversias, éstas deberán definirse por la vía del arbitraje.
                                    <br />
                                    Las partes, de común acuerdo, podrán nombrar un perito único; si no hubiera acuerdo, cada parte nombrará el suyo y un tercero dirirnidor. Este último será designado por el Juez, si las partes no acuerdan su nombramiento.
                                    <br />
                                    Las controversias de derecho suscitadas entre las partes sobre la naturaleza y alcance del contrato de seguro, serán resueltas únicamente por la vía del arbitraje de acuerdo a lo previsto en la Ley N° 708 de 25 de junio de 2015.
                                    <br />
                                    La Autoridad de Fiscalización y Control de Pensiones y Seguros podrá fungir corno instancia de conciliación, para todo siniestro cuya cuantía no supere el monto de UFV 100.000,00.- (Cien Mil 00/100 Unidades de Fomento a la Vivienda). Si por esta vía y considerando dicha cuantía, no existiera un acuerdo, la Autoridad de Fiscalización y Control de Pensiones y Seguros podrá conocer y resolver la controversia por Resolución Administrativa debidamente motivada.
                                </td>
                            </tr>
                        </table>
                        <br />
                        <div style="text-align: center;font-weight: bold;">
                            COMPAÑÍA DE SEGUROS DE VIDA FORTALEZA S.A.
                        </div>
                    </div>
                    @if($detail->cesantia == true)
                        <page>
                            <div style="page-break-before: always;">&nbsp;</div>
                        </page>
                        @include('cert.de.cesantia-hip-v2')
                    @endif
                @endif

            @endif
            @var $nro_detail++
        @endforeach

        @if($format === 'pdf' && ($c < $copies))
            <page>
                <div style="page-break-before: always;">&nbsp;</div>
            </page>
        @endif
    @endfor
</div>