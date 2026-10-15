@var $copies = 1;
@var $num_pre = 0;
@if($format === 'pdf' && $header->issued)
    @var $copies = $header->copy == 0 ? 1 : $header->copy;
@endif
<style>
    .cuadre{
        border-bottom: 1px solid #000000;
        border-top: 1px solid #000000;
        border-left: 1px solid #000000;
        border-right: 1px solid #000000;
        text-align: center;
    }
</style>
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
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                        <tr>
                            <td style="width: 25%;">
                                {{-- <img src="{{ base_path('public/' . $header->retailerProduct->companyProduct->company->image) }}"
                                style="margin-top: -10px; margin-left: 0px; position: fixed;" width="130"><br /> --}}
                                <img src="{{ base_path('public/images/logoprincipal.jpg') }}"
                                style="margin-top: -10px; margin-left: 0px; position: fixed;" width="140"><br />
                            </td>
                            <td style="width: 50%; text-align: center;">
                            </td>
                            <td style="width: 25%;">
                                <span style="font-size: 60%;">
                                    @if($header->issued && $copies > 1)
                                        @if($c === 0)
                                            ORIGINAL CLIENTE
                                        @else
                                            COPIA {{ $c === 1 ? 'BANCO' : ($c === 2 ? 'CIA.' : $c) }}
                                        @endif
                                        - Usama R.L.
                                    @elseif($header->issued && $copies === 1)
                                        ORIGINAL CLIENTE - {{ $header->retailerProduct->retailer->name }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%; text-align: center;"></td>
                            <td style="width: 50%; text-align: center;">
                                <strong>DECLARACION JURADA DE SALUD N° {{ $header->issue_number }}</strong><br>
                                <strong>SOLICITUD DE SEGURO DE DESGRAVAMEN HIPOTECARIO</strong>
                            </td>
                            <td style="width: 25%;"></td>
                        </tr>
                </table>

            <div style="text-align: center;font-size: 60%;">
                Formato registrado por la Autoridad de Supervisión del Sistema Financiero mediante resolución ASFI Nº 180/2011 del 24/02/2011 
                    <br>206-934901-2000 03 006 4011
            </div>
            
            <div style="font-size: 50%;">
                <strong>Estimado Cliente, agradeceremos completar la información que se requiere a continuación: (utilice letra clara)</strong>
                <br>
                <strong>DATOS PERSONALES:</strong>
                <br />
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 10%;">Nombre completo: </td>
                        <td style="width: 20%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->first_name }}
                        </td>
                        <td style="width: 30%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->last_name }}
                        </td>
                        {{-- <td style="width: 35%; border-bottom: 1px dotted #000000; text-align: center;"> --}}
                            {{-- //cambio antes de totular y conyuge --}}
                            {{-- {{ $detail->client->mother_last_name }}
                            @if($detail->client->gender === 'F' && ! empty($detail->client->married_name))
                            @if($detail->client->civil_status === 'C')
                            {{ $detail->client->married_name }}
                            @elseif($detail->client->civil_status === 'V')
                            vda. de {{ $detail->client->married_name }}
                            @endif
                            @endif --}}    
                            {{-- // Agregando otra celda dentro de otra celda para alinear el texto de titular y conyuge nuevo --}}
                        <td style="width: 35%; border-bottom: 1px dotted #000000; text-align: center; white-space: nowrap;">

                            {{ $detail->client->mother_last_name }}

                            @if($detail->client->gender === 'F' && !empty($detail->client->married_name))
                                @if($detail->client->civil_status === 'C')
                                    {{ $detail->client->married_name }}
                                @elseif($detail->client->civil_status === 'V')
                                    vda. de {{ $detail->client->married_name }}
                                @endif
                            @endif

                            {{-- &nbsp;&nbsp;&nbsp; --}}

                        <table style="display:inline-table;">
                            <tr>
                                <td style="vertical-align:middle;">
                                    <span style="border:1px solid #000;padding:0 2px;">
                                        {{ $detail->headline === 'D' ? 'X' : '&nbsp;' }}
                                    </span>
                                    TITULAR
                                </td>

                                <td style="padding-left:15px; vertical-align:middle;">
                                    <span style="border:1px solid #000;padding:0 2px;">
                                        {{ $detail->headline === 'C' ? 'X' : '&nbsp;' }}
                                    </span>
                                    CÓNYUGE/CODEUDOR
                                </td>
                            </tr>
                        </table>
                        </td>
                        {{-- </td> --}}
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 15%;">Fecha de Nacimiento: </td>
                        <td style="width: 20%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->birthdate->format('d/m/Y') }}
                        </td>
                        <td style="width: 5%; text-align: center;">Edad: </td>
                        <td style="width: 5%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->age }}
                        </td>
                        <td style="width: 5%; text-align: center;">Sexo: </td>
                        <td style="width: 15%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->gender }}
                        </td>
                        <td style="width: 15%; text-align: center;">Carné de Identidad: </td>
                        <td style="width: 10%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->dni . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }} {{  $detail->client->extension=='SE'?'':$detail->client->extension }}
                        </td>
                        <td style="width: 5%; text-align: center;">Origen: </td>
                        <td style="width: 10%; border-bottom: 1px dotted #000000; text-align: center;">
                             
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 5%;">Peso: </td>
                        <td style="width: 10%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->weight }} kg.
                        </td>
                        <td style="width: 10%; text-align: center;">Estatura: </td>
                        <td style="width: 20%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->height }} cm.
                        </td>
                        <td style="width: 5%; text-align: center;">Celular: </td>
                        <td style="width: 20%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->phone_number_mobile }}
                        </td>
                        <td style="width: 10%; text-align: center;">Telf. Oficina: </td>
                        <td style="width: 20%; border-bottom: 1px dotted #000000; text-align: center;">
                             
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 15%;">Correo Electrónico: </td>
                        <td style="width: 50%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->email }}
                        </td>
                        <td style="width: 10%; text-align: center;">Telf. Domicilio: </td>
                        <td style="width: 25%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->phone_number_home }}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 10%;">Ocupación: </td>
                        <td style="width: 60%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->activity->occupation }}
                        </td>
                        <td style="width: 10%; text-align: center;">Profesión: </td>
                        <td style="width: 20%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->occupation_description }}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 15%;">Lugar de Trabajo: </td>
                        <td style="width: 85%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->business_address }}
                        </td>
                    </tr>
                </table>
                
                <strong>CUESTIONARIO:</strong><br />
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 5%;" ></td>
                        <td style="width: 65%;"></td>
                        <td style="width: 20%; text-align: center;" colspan="2">
                        </td>
                        <td style="width: 10%;"> </td>
                    </tr>
                    <tr>
                        <td style="width: 5%;" ></td>
                        <td style="width: 65%;"></td>
                        <td style="width: 10%; text-align: center;"><strong>Sí</strong></td>
                        <td style="width: 10%; text-align: center;"><strong>No</strong></td>
                        <td style="width: 10%;"> </td>
                    </tr>
                    @var $responses = json_decode($detail->response->response, true);

                    @foreach($responses as $key => $response)
                        @if($key == 5)
                            <tr>
                                <td style="width: 5%;"></td>
                                <td style="width: 65%;"><strong>DURANTE LOS ÚLTIMOS CINCO AÑOS:</strong></td>
                                <td style="width: 10%;"></td>
                                <td style="width: 10%;"></td>
                                <td style="width: 10%;"></td>
                            </tr>
                        @endif
                    <tr>
                        <td style="width: 5%; text-align: right;" valign="top">{{ $key }}.</td>
                        <td style="width: 65%; padding-left: 10px;">
                            {{ $response['question'] }}
                        </td>
                        <td style="width: 10%;" valign="top">
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 40%;" valign="top"></td>
                                    <td style="width: 20%; height: 6px; border: 1px solid #000000; text-align: center;">
                                        {{ $response['response'] ? 'x' : '&nbsp;' }}
                                    </td>
                                    <td style="width: 40%;"></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;" valign="top">
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 40%;" valign="top"></td>
                                    <td style="width: 20%; height: 6px; border: 1px solid #000000; text-align: center;">
                                        {{ ! $response['response'] ? 'x' : '&nbsp;' }}
                                    </td>
                                    <td style="width: 40%;"></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;"> </td>
                    </tr>
                    @endforeach
                </table>
                <br />
                <strong>SI ALGUNA DE SUS RESPUESTAS (de la N° 2 a la N° 7) ES AFIRMATIVA, FAVOR BRINDAR DETALLES:</strong>
                <br>
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    @foreach($responses as $key => $response)
                        @if (isset($response["response_specification"]))
                            @if ($response["response_specification"] != '')
                                <tr> 
                                    <td style="width: 100%; border-bottom: 1px dotted #000000;">
                                        {{ $key }}. {{ $response["response_specification"] }}
                                    </td>
                                </tr>
                                <span style="color: #ffffff">
                                    @php
                                    $num_pre = $num_pre + 1;
                                @endphp
                                </span>
                            @endif                            
                        @endif
                    @endforeach
                    @if ($num_pre == 0)
                        <tr> 
                            <td style="width: 100%; border-bottom: 1px dotted #000000; text-align: center;">
                                &nbsp;
                            </td>
                        </tr>
                        <tr> 
                            <td style="width: 100%; border-bottom: 1px dotted #000000; text-align: center;">
                                &nbsp;
                            </td>
                        </tr>                        
                    @endif
                </table>
                <br>
                <strong>BENEFICIARIO PARA LA COBERTURA DE SEPELIO:</strong>
                {{ $detail->beneficiary->full_name }} - {{ $detail->beneficiary->relationship }}
                <br>
                Declaro haber contestado con total veracidad, máxima buena fe a todas las preguntas del presente cuestionario y no haber omitido u ocultado hechos y/o circunstancias que hubiera podido influir en la celebración del contrato de seguro. Las declaraciones de salud que hacen anulable el Contrato de Seguros y en la que el asegurado pierde su derecho a indemnización, se enmarcan en los artículos 992, 993, 999 y 1038 del Código de Comercio.
                <br>
                Relevo expresamente del secreto profesional y legal, a cualquier médico que me hubiese asistido y/o tratado de dolencias y le autorizo a revelar a Nacional Seguros Vida y Salud S.A. todos los datos y antecedentes patológicos que pudiera tener o de los que hubiera adquirido conocimiento al prestarme sus servicios. Entiendo que de presentarse alguna eventualidad contemplada bajo la póliza de seguro como consecuencia de alguna enfermedad existente a la fecha de la firma de este documento o cuando haya alcanzado la edad límite estipulada en la póliza, la compañía aseguradora quedará liberada de toda la responsabilidad en lo que respecta a mí seguro. Finalmente, declaro conocer en su totalidad lo estipulado en mi póliza de seguro.
                <br>
                <u>NOTA IMPORTANTE:</u>
                <br>
                La firma Infra del solicitante, es también valida jurídicamente para el certificado individual del seguro de desgravamen que consta en el reverso, sin lugar a reclamo alguno.
                                
                <br>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                    <tr>
                        <td style="width: 48%;">
                            Lugar y Fecha: <u>{{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }} {{ ! is_null($header->date_issue) ? $header->date_issue->format('d/m/Y') : '' }}</u>
                            <br>
                            <strong>
                                DEL CREDITO SOLICITADO:
                                <br>
                                (a ser completado por  el oficial de crédito de la entidad financiera)
                            </strong>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 40%; ">
                                        <strong>Monto Actual Solicitado</strong>
                                    </td>
                                    <td style="width: 4%;"  >
                                        {{-- {{ $header->currency=='USD'?'X':'&nbsp;' }} --}}
                                    </td>
                                    <td style="width: 4%;">
                                        {{-- USD --}}
                                    </td>
                                    <td style="width: 4%;">
                                        
                                    </td>
                                    <td style="width: 48%;">
                                        {{-- &nbsp; --}}
                                        <strong>Monto Total Acumulado</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 40%; border-bottom: 1px dotted #000000; text-align: center;">
                                        {{ $header->amount_requested }}
                                    </td>
                                    <td style="width: 4%;" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 4%;">
                                        
                                    </td>
                                    <td style="width: 4%;"></td>
                                    <td style="width: 48%;">
                                        &nbsp;
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 40%;">
                                    </td>
                                    <td style="width: 4%;" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 4%;">
                                        
                                    </td>
                                    <td style="width: 4%;"></td>
                                    <td style="width: 48%;border-bottom: 1px dotted #000000; text-align: center;">
                                        &nbsp;
                                        {{ number_format(mountByCurrency('USD', $detail->cumulus, $header->retailerProduct->retailer->exchangeRate), 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 40%;">
                                        
                                    </td>
                                    <td style="width: 4%;" class="cuadre" >
                                        {{ $header->currency=='BS'?'X':'&nbsp;' }}
                                    </td>
                                    <td style="width: 4%;">
                                        Bs.
                                    </td>
                                    <td style="width: 4%;"></td>
                                    <td style="width: 48%;">
                                        (expresado en Bolivianos) 
                                    </td>
                                </tr>
                            </table>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 40%;" valign="top">PLAZO PRESENTE CRÉDITO:</td>
                                    <td style="width: 60%; border-bottom: 1px dotted #000000; text-align: center;">
                                        {{ $header->term }} {{ $header->type_term }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 40%;" valign="top">TIPO DE OPERACIÓN:</td>
                                    <td style="width: 60%; border-bottom: 1px dotted #000000; text-align: center;"></td>
                                </tr>
                            </table>
                            <br><br>
                            <br><br>
                            <br><br>
                            <br><br>
                            <br><br>
                            <center>
                                OFICIAL DE CRÉDITO 
                                <br>
                                FIRMA Y SELLO
                            </center>
                        </td>
                        <td style="width: 4%;"></td>
                        <td style="width: 48%;">
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 5%;" >Firma:</td>
                                    <td style="width: 95%; border-top: none !important; border-left: none !important; border-right: none !important; border-bottom: 1px dotted #000000 !important; text-align: center;"></td>
                                </tr>
                            </table>
                            <br>
                            <strong>EVALUACIÓN COMPAÑÍA</strong>
                            <br>
                            <br>
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 30%; ">
                                        RIESGO NORMAL
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;" class="cuadre" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td style="width: 10%;"></td>
                                </tr>
                            </table>
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 30%; ">
                                        RIESGO AGRAVADO
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;" class="cuadre" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;border-bottom: 1px dotted #000000; text-align: center;"></td>
                                    <td style="width: 10%;border-bottom: 1px dotted #000000; text-align: center;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; ">
                                        
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;">porcentaje</td>
                                    <td style="width: 10%;">motivo</td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; ">
                                        
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;">
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td style="width: 10%;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; ">
                                        RIESGO RECHAZADO
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;" class="cuadre" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td style="width: 10%;border-bottom: 1px dotted #000000; text-align: center;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; ">
                                        
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;">
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td style="width: 10%;">motivo</td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; ">
                                        RIESGO APLAZADO
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;" class="cuadre" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;border-bottom: 1px dotted #000000; text-align: center;"></td>
                                    <td style="width: 10%;border-bottom: 1px dotted #000000; text-align: center;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; ">
                                        
                                    </td>
                                    <td style="width: 5%; ">
                                        &nbsp;
                                    </td>
                                    <td style="width: 5%;" class="" >
                                        &nbsp;
                                    </td>
                                    <td style="width: 10%;">tiempo</td>
                                    <td style="width: 10%;">Motivo</td>
                                </tr>
                            </table>
                            <br><br>
                            <center>
                                COMPAÑÍA 
                            <br>
                            FIRMA Y SELLO 
                            </center>
                        </td>
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
                                @if(!in_array($header->creditProduct->slug, ['PMO', 'PLT']))
                                    {{ $detail->facultative->percentage_bank }}
                                @else
                                    {{ $detail->facultative->percentage }}
                                @endif
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
            <div style="font-size: 50%; margin-top: 5px; text-align: justify;">
                <br>
                <strong>NACIONAL SEGUROS VIDA Y SALUD S.A.</strong>, Santa Cruz Tel. Piloto: (591-3) 371-6262. Fax: (591-3) 3337969 – La Paz Tel. Piloto (591-2) 244-2942. Fax: (591-2) 244-2905 - Cochabamba Tel. Piloto: (591-4) 448-7050. Fax:(591-4) 448-7054 – Sucre Tel. Piloto (591-4) 642-5197. Fax: (591-4) 642-5196-Beni Tel/fax (591-3) 462 2500
                <br>
                206-934901-2000 03 006-3012
            </div>
            
                @if($detail->approved && $header->issued && ! $header->canceled)
                    <page>
                        <div style="page-break-before: always;">&nbsp;</div>
                    </page>
                    @if(isFacultativeAccidentalDeath($header, $detail) == false)
                    <header style="text-align: right;">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate(getUrlQr(encode($header->retailerProduct->id), $header))) !!} "
                        style="margin-top: -40px; margin-left: 0px;">
                    </header>
                    <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                            <tr>
                                <td style="width: 25%;">
                                    {{-- <img src="{{ base_path('public/' . $header->retailerProduct->companyProduct->company->image) }}"
                                    style="margin-top: -10px; margin-left: 0px; position: fixed;" width="130"><br /> --}}
                                    <img src="{{ base_path('public/images/logoprincipal.jpg') }}"
                                style="margin-top: -10px; margin-left: 0px; position: fixed;" width="140"><br />
                                </td>
                                <td style="width: 50%; text-align: center;">
                                </td>
                                <td style="width: 25%;">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 25%; text-align: center;"></td>
                                <td style="width: 50%; text-align: center;">
                                    <strong>CERTIFICADO INDIVIDUAL DE SEGURO DE DESGRAVAMEN HIPOTECARIO N° {{ $header->issue_number }}</strong>
                                    <br>
                                    Formato registrado por la Autoridad de Supervisión del Sistema Financiero mediante resolución ASFI Nº 180/2011 del 24/02/2011 206-934901-2000 03 006 4011
                                    <br>
                                    <strong>POLIZA DE SEGURO DE DESGRAVAMEN HIPOTECARIO Nº {{ $header->policy_number }}</strong>

                                </td>
                                <td style="width: 25%;"></td>
                            </tr>
                    </table>
                        <div style="margin: 1mm 1mm 1mm 1mm;text-align: justify; font-size: 7px;">
                            NACIONAL SEGUROS VIDA Y SALUD S.A., (denominada en adelante la “Compañía”), por el presente CERTIFICADO INDIVIDUAL DE SEGURO ha ce constar que la persona nominada en el anverso de la
                            declaración jurada de salud (denominado) en adelante el “Asegurado”), está protegido por la Póliza de Seguro de Vida de Desgravamen arriba mencionada, de acuerdo a las siguientes Condiciones Particulares:
                            <br>  
                            <strong>CONTRATANTE Y BENEFICIARIO      CONTRATANTE Y BENEFICIARIO	COOPERATIVA DE AHORRO Y CRÉDITO SOCIETARIA "UNIÓN SANTIAGO DE MACHACA USAMA" R.L.</strong>	
                            <br>
                            <br>
                            <strong>COBERTURAS Y CAPITALES ASEGURADOS:</strong>
                            <br>
                            <u>COBERTURA DE MUERTE NATURAL O ACCIDENTAL:</u> Este seguro cubre el saldo insoluto de la deuda contraída por el Asegurado con el "Tomador" a la fecha de la eventualidad prevista, siempre que la causa no haya sido excluida en el presente Contrato.
                            <br>
                            <u>COBERTURA DE INVALIDEZ TOTAL PERMANENTE:</u> Este seguro cubre el saldo insoluto de la deuda contraída por el asegurado, si por accidente o enfermedad, el asegurado sufriere incapacidad total y permanente que lo obligue a abandonar su empleo u ocupación, impidiéndole ejercer otra actividad conforme a su posición social a sus conocimientos y sus aptitudes, a juicio de los médicos nombrados por el asegurador, siempre que la causa no haya sido excluida en el presente contrato.
                            <br>
                            <u>SEPELIO:</u> Este seguro indemniza un Monto de Bs. 2,500.00 (Dos mil Quinientos 00/100 Bolivianos) Aplicable a los prestatarios declarados en los reportes mensuales.
                            <br>
                            <br>
                            <strong>LIMITES DE EDAD:</strong>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 25%; "><u>Cobertura Básica de Vida y Sepelio</u></td>
                                    <td style="width: 25%; "></td>
                                    <td style="width: 25%; "><u>Coberturas De Invalidez:</u></td>
                                    <td style="width: 25%; "></td>
                                </tr>
                            </table>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 25%; ">De Ingreso:</td>
                                    <td style="width: 25%; ">Desde 18 años, hasta los 70 años</td>
                                    <td style="width: 25%; ">De ingreso:</td>
                                    <td style="width: 25%; ">Desde  18 años, hasta los 65 años</td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; ">De permanencia</td>
                                    <td style="width: 25%; ">Hasta 76 años</td>
                                    <td style="width: 25%; ">De permanencia:</td>
                                    <td style="width: 25%; ">Hasta  70 años</td>
                                </tr>
                            </table>
                            <br>
                            <strong>EXCLUSIONES:</strong>
                            <br>
                            Guerra internacional o civil declarada o no. 
                            <br>
                            Fisión nuclear.
                            <br>
                            Enfermedad grave congénita o enfermedad preexistente, entendiéndose como tal aquella que sea la causa u origen de la muerte, que se ha originado con anterioridad a la contratación del seguro y de la cual el asegurado tenía 
                            conocimiento y ha recibido tratamiento médico, o le recomendaron exámenes médicos para diagnóstico, o tomó medicamentos recetadas por un médico, dentro de los dos primeros años de vigencia de su cobertura; en consecuencia,  
                            este riesgo quedará cubierto a partir del primer día del tercer año de vigencia de la cobertura para cada asegurado. Para efectos de determinación de la preexistencia la o las fechas de origen en cada operación serán evaluadas individualmente.
                            <br>
                            Suicidio practicado por el Asegurado dentro del primer año de vigencia de su cobertura. En consecuencia, en consecuencia, este riesgo quedará cubierto a partir del primer día del segundo año de vigencia de la cobertura para cada Asegurado
                            <br>
                            <br>
                            <strong>TASA MENSUAL PORCENTUAL Y FORMA DE PAGO: (*)</strong>
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 30%; text-align: center; border: 1px solid #8a8a8a;"><strong>ASEGURADOS</strong></td>
                                    <td style="width: 25%; text-align: center; border: 1px solid #8a8a8a;"><strong>DESCRIPCION</strong></td>
                                    <td style="width: 45%; text-align: center; border: 1px solid #8a8a8a;"><strong>COBERTURA DE DESGRAVAMEN</strong></td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; border: 1px solid #8a8a8a;">Titular o Individual</td>
                                    <td style="width: 25%; border: 1px solid #8a8a8a;">TASA DE LA COMPAÑÍA</td>
                                    <td style="width: 45%; border: 1px solid #8a8a8a;">1.04‰ (POR MIL) – Desgravamen c/u</td>
                                </tr>
                                <tr>
                                    <td style="width: 30%; border: 1px solid #8a8a8a;">Codeudores / Mancómunos</td>
                                    <td style="width: 25%; border: 1px solid #8a8a8a;">TASA DE LA COMPAÑÍA</td>
                                    <td style="width: 45%; border: 1px solid #8a8a8a;">0.82‰ (POR MIL) – Desgravamen c/u</td>
                                </tr>
                            </table>
                            <br>
                            En caso de que el Tomador como administrador de este seguro colectivo, cargase primas por Asegurados incorporados que no hayan cumplido con las condiciones, definiciones y requisitos de asegurabilidad para la admisión establecidos en esta póliza, o por no haber sometido en consideración del Asegurador de Seguros los formularios de declaración que contengan respuestas afirmativas en lo referente a enfermedades, o cargase primas por capitales asegurados superiores a la responsabilidad máxima del Asegurador, la responsabilidad máxima del Tomador y del Asegurador frente a eventuales reclamaciones derivadas por el prestatario y/o su representante se limitara a la devolución de la totalidad de las primas cobradas y pagadas por tales incorporaciones o primas cobradas y pagadas en exceso.
                            <br>
                            <strong>ADHESION VOLUNTARIA DEL ASEGURADO:</strong>
                            <br>
                            El asegurado al momento de concretar el crédito con el Contratante declara su consentimiento VOLUNTARIO para ser asegurado con la póliza arriba indicada, cuya indemnización en caso de siniestro será a favor
                            del Contratante, hasta el monto del saldo insoluto de la deuda o el Limite de la Tarjeta de Crédito a la fecha de fallecimiento.
                            <br>
                            <strong>CONTRATO PRINCIPAL (PÓLIZA MATRIZ):</strong>
                            <br>
                            Todos los beneficios y contratiempos a los cuales tiene derecho el Asegurado, están sujetos a lo estipulado en las Condiciones Generales, Especiales y Particulares de la póliza matriz en virtud de la cual se regula este seguro, se efectúan los pagos de primas y se mantiene en su vigencia.  La firma del asegurado, el hecho de la posesión o el  mero uso que el Asegurado haga de este Certificado, implica la tácita aceptación por
                            su parte de todas las condiciones de la póliza matriz, tanto en lo que le beneficia como en lo que lo obliga.
                            <br>
                            <strong>CORREDOR DE SEGUROS:</strong>
                            <br>
                            Razón Social: Sudamericana S.R.L - Corredores y Asesores de Seguros	        Dirección: La Paz | Av. Arce Pasaje Cordero No. 163 – Zona: San Jorge	Teléfono: +591-2-2433500
                            <br>
                            <strong>COMPAÑÍA ASEGURADORA:</strong>
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 50%; ">
                                        Razón Social: NACIONAL SEGUROS VIDA Y SALUD SA.          <br>
                                        Dirección Oficina Central (Santa Cruz): Av. Cristóbal de Mendoza N°333, esquina Av. Alemania. <br>
                                        Oficina Cochabamba: Av. America Este Nº 397 Acera Norte, casi esq. Plazuela 4 de Noviembre <br>
                                        Oficina La Paz: Av. Costanera Nº 21 entre calles 8 y 9 de Calacoto, Edificio Nacional Seguros <br>
                                        Oficina Sucre: Calle Olañeta Nº 32 Plazuela Zudáñez <br>
                                        Oficina Trinidad: Calle 18 de Noviembre Nº 154, <br>
                                        Oficina Tarija Calle Ingavi Nº 552

                                    </td>
                                    <td style="width: 50%; ">
                                        Teléfono Oficina Central: 591-3-3716262
                                        <br>
                                        Facsímil Oficina Central: 591-3- 3337969
                                        <br>
                                        Teléfono: 4457100   Fax: 4457104
                                        <br>
                                        Teléfono: 2442942   Fax: 2442905 –
                                        <br>
                                        Teléfono: 6425197   Fax: 6425196
                                        <br>
                                        Teléfono: 4634109   Fax: 4634112 –
                                        <br>
                                        Teléfono: 66-66929   Fax: 66-66929
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <strong>
                                La cobertura de la póliza se activará a partir del momento del desembolso del préstamo ó de su reprogramación y al momento de la aceptación por parte de la compañía para los casos en los que correspondan los requisitos médicos.
                                <br>
                                (*)     Tasas variables sujetas a las renovaciones que realice el Banco con la Aseguradora. Asimismo, se aclara que la compañía podrá aplicar extra primas adicionales del 25%, 35 %, 50%, 75% y 100% dependiendo del estado de salud del propuesto asegurado, dicho recargo se hará consta mediante nota indicando el porcentaje y motivo.
                            </strong>
                            <center>
                                <strong>
                                    NACIONAL SEGUROS VIDA Y SALUD S.A.
                                    <br>
                                    FIRMAS AUTORIZADAS
                                </strong>
                            </center>
                            <table border="0" cellpadding="1" cellspacing="0"
                                style="width: 100%; font-weight: normal; font-size: 11px; font-family: Arial, Helvetica, sans-serif; ">
                                <tr>
                                    <td style="width: 10%">
                                    </td>
                                    <td style="width: 30%; text-align: center;">
                                        <img src="{{ base_path('public/images/primerafirma.jpg') }}" width="100">
                                    </td>
                                    <td style="width: 20%">
                                    </td>
                                    <td style="width: 30%; text-align: center;">
                                        <img src="{{ base_path('public/images/segundafirma.jpg') }}" width="100">
                                    </td>
                                    <td style="width: 10%">
                                    </td>
                                </tr>
                            </table>
                            <br>
                            Nacional Seguros Vida y Salud S.A, otorga la validez jurídica que corresponda, a las firmas digitales de sus representantes que constan en el presente certificado
                            <br>
                            <br>
                            <strong>PROCEDIMIENTO A SEGUIR EN CASO DE SINIESTRO:</strong>
                            <br>
                            Para reclamar el pago de cualquier indemnización con cargo a esta póliza, el Contratante deberá remitir a la Compañía la solicitud del Beneficiario, junto con las pruebas de siniestro correspondiente. La Compañía podrá, a sus expensas, recabar Informes o pruebas complementarias. En caso de siniestros contemplados bajo el presente contrato, el asegurado debe presentar:
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                <tr>
                                    <td style="width: 55%; vertical-align: top;">
                                        <strong>A) En caso de fallecimiento:</strong>
                                        <br>
                                        Cédula de identidad
                                        <br>
                                        Certificado de defunción original
                                        <br>
                                        Certificado médico único de defunción original o copia legalizada
                                        <br>
                                        Fotocopia de la autoridad competente (para los casos de muerte accidental o suicidio) 
                                        <br>
                                        Declaración jurada de salud
                                        <br>
                                        Historia Clínica (si corresponde)                                        
                                        <br>
                                        <strong>B) En caso de Invalidez Total y Permanente:</strong>
                                        <br>
                                        Certificado de Incapacidad Total y Permanente por accidente o enfermedad expedida por un médico autorizado por la APS, con sus debidos respaldos que sustenten la invalidez.
                                        <br>
                                        Atestado policial en caso de Incapacidad Total y Permanente por Accidente
                                    </td>
                                    <td style="width: 5%;"></td>
                                    <td style="width: 40%; vertical-align: top;">
                                        <strong>D) Para la cobertura de Sepelio:</strong>
                                        <br>
                                        Fotocopia Certificado Defunción
                                        <br>
                                        Fotocopia de DJS donde se indica el nombre del Beneficiario
                                        <br>
                                        Fotocopia de C.I. del Beneficiario
                                    </td>
                                </tr>
                            </table>
                            <br>
                            En caso de ser necesario, el Asegurador se reserva el derecho de exigir cualquier otra información adicional que pudiese ayudar a determinar el pago del siniestro.
                            <br>
                            Una vez recibidos todos los documentos justificativos, la Compañía se pronunciará expresando su conformidad o denegación sobre la base de lo estipulado en las condiciones de la póliza matriz y en casos de conformidad satisfará la indemnización al contratante, dentro de los quince (15) días hábiles posteriores a la recepción de la última documentación presentada.
                            <br>
                            206-934901-2000 03 006-4011
                            
                        </div>
                        
                    @else
                        
                    @endif
                    @if($detail->cesantia == true)
                        {{-- <page>
                            <div style="page-break-before: always;">&nbsp;</div>
                        </page>
                        @include('cert.de.cesantia-v1') --}}
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