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
                                <img src="{{ base_path('public/' . $header->retailerProduct->companyProduct->company->image) }}"
                                style="margin-top: -10px; margin-left: 0px; position: fixed;" width="115"><br />
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
                                        {{-- - {{ $header->retailerProduct->retailer->name }} --}}
                                        - Usama R.L.
                                    @elseif($header->issued && $copies === 1)
                                        ORIGINAL CLIENTE - {{ $header->retailerProduct->retailer->name }}
                                    @endif
                                    {{--<br>
                                    [MODELO L]--}}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25%; text-align: center;"></td>
                            <td style="width: 50%; text-align: center;">
                                DECLARACION JURADA DE SALUD N˚ <br>
                                SOLICITUD DE SEGURO DE DESGRAVAMEN
                            </td>
                            <td style="width: 25%;"></td>
                        </tr>
                </table>

            <div style="text-align: center;font-size: 60%;">
                <u>Formato aprobado por la Superintendencia de Pensiones, Valores y Seguros mediante Resolución Administrativa Nº 161/09 del 20 de Marzo de 2009</u>
            </div>
            
            <div style="font-size: 50%;">
                <strong>Estimado Cliente, agradeceremos completar la información que se requiere a continuación: (utilice letra clara)</strong>
                <br>
                <strong>DATOS PERSONALES</strong>
                <br />
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 10%;">Nombre completo: </td>
                        <td style="width: 20%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->first_name }}
                        </td>
                        <td style="width: 35%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->last_name }}
                        </td>
                        <td style="width: 35%; border-bottom: 1px dotted #000000; text-align: center;">
                            {{ $detail->client->mother_last_name }}
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
                        <td style="width: 15%; text-align: center;">Carnét de Identidad: </td>
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
                        <td style="width: 10%; text-align: center;">Telf Oficina: </td>
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
                        <td style="width: 10%; text-align: center;">Telf Dom: </td>
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
                
                <strong>CUESTIONARIO</strong><br />
                <table cellpadding="0" cellspacing="0" style="width: 100%; font-size: 50%;">
                    <tr>
                        <td style="width: 5%;" ></td>
                        <td style="width: 65%;"></td>
                        <td style="width: 20%; text-align: center;" colspan="2">
                            <!--<strong>TITULAR {{$nro_detail}}</strong>-->
                        </td>
                        <td style="width: 10%;"> </td>
                    </tr>
                    <tr>
                        <td style="width: 5%;" ></td>
                        <td style="width: 65%;"></td>
                        <td style="width: 10%; text-align: center;"><strong>SI</strong></td>
                        <td style="width: 10%; text-align: center;"><strong>NO</strong></td>
                        <td style="width: 10%;"> </td>
                    </tr>
                    @var $responses = json_decode($detail->response->response, true);

                    @foreach($responses as $key => $response)
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
                <strong>SI ALGUNA DE SUS RESPUESTAS ( de la N° 2 a la N° 7 ) ES AFIRMATIVA, FAVOR BRINDAR DETALLES</strong>
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
                <strong>BENEFICIARIO PARA LA COBERTURA DE SEPELIO.-</strong> Familiares de primer grado: (Padres, cónyuge o hijo mayor de edad):
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
                                (a ser completado por  el oficial de crédito de la entidad financiera)
                            </strong>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 40%; ">
                                        
                                    </td>
                                    <td style="width: 4%;" class="cuadre" >
                                        {{ $header->currency=='USD'?'X':'&nbsp;' }}
                                    </td>
                                    <td style="width: 4%;">
                                        USD
                                    </td>
                                    <td style="width: 4%;"></td>
                                    <td style="width: 48%;">
                                        &nbsp;
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
                                        (expresado en dólares americanos) 
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
                            EVALUACIÓN COMPAÑÍA
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
                                    <img src="{{ base_path('public/' . $header->retailerProduct->companyProduct->company->image) }}"
                                    style="margin-top: -10px; margin-left: 0px; position: fixed;" width="115"><br />
                                </td>
                                <td style="width: 50%; text-align: center;">
                                </td>
                                <td style="width: 25%;">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 25%; text-align: center;"></td>
                                <td style="width: 50%; text-align: center;">
                                    CERTIFICADO INDIVIDUAL DE SEGURO DE VIDA DE DESGRAVAMEN No {{ $header->issue_number }}
                                    <br>
                                    Formato aprobado por la Superintendencia de Pensiones, Valores y Seguros mediante Resolución Administrativa N°161/09 del 20 de Marzo de 2009
                                    <br>
                                    <strong>POLIZA DE SEGURO DE VIDA DESGRAVAMEN</strong>

                                </td>
                                <td style="width: 25%;"></td>
                            </tr>
                    </table>
                        <div style="margin: 1mm 1mm 1mm 1mm;text-align: justify;">
                            NACIONAL SEGUROS VIDA Y SALUD S.A., (denominada en adelante la “Compañía”), por el presente CERTIFICADO INDIVIDUAL DE SEGURO ha ce constar que la persona nominada en el anverso de la
                            declaración jurada de salud (denominado) en adelante el “Asegurado”), está protegido por la Póliza de Seguro de Vida de Desgravamen arriba mencionada, de acuerdo a las siguientes Condiciones Particulares:
                            <br>
                            <strong>CONTRATANTE:</strong>	COOPERATIVA DE AHORRO Y CREDITO SOCIETARIA UNION SANTIAGO DE MACHACA USAMA RL
                            <br>
                            <strong>BENEFICIARIO:</strong>	 COOPERATIVA DE AHORRO Y CREDITO SOCIETARIA UNION SANTIAGO DE MACHACA USAMA RL 
                            <br>
                            <strong>BENEFICIARIO COBERTURA DE SEPELIO:</strong> {{ $detail->beneficiary->full_name }}
                            <br>
                            <br>
                            <strong>COBERTURAS Y CAPITALES ASEGURADOS:</strong>
                            <br>
                            <u>Cobertura Básica de Vida:</u>
                            <br>
                            Muerte natural o accidental hasta el saldo insoluto de la deuda a la fecha del fallecimiento del asegurado. 
                            <br>
                            Coberturas Complementarian:
                            <br>
                            Pago Anticipado del Capital Asegurado por Incapacidad Total y Permanente hasta el saldo insoluto de la deuda a la fecha del Dictamen de la Incapacidad del asegurado 
                            <br>
                            Sepelio: Bajo esta cobertura, la compañía cancelara por concepto de gastos de sepelio el importe de $us. 300.- (Trescientos 00/100 Dólares Americano) o Bs. 2.100 (dos mil cien 00/100 bolivianos)
                            <br>
                            <strong>LIMITES DE EDAD:</strong>
                            <br>
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
                                    <td style="width: 25%; ">Desde 18 años, hasta los 70 años y 364 días</td>
                                    <td style="width: 25%; ">De ingreso:</td>
                                    <td style="width: 25%; ">Desde  18 años, hasta los 65 años</td>
                                </tr>
                                <tr>
                                    <td style="width: 25%; ">De permanencia</td>
                                    <td style="width: 25%; ">Hasta 75 años</td>
                                    <td style="width: 25%; ">De permanencia:</td>
                                    <td style="width: 25%; ">Hasta  70 años</td>
                                </tr>
                            </table>
                            <br>
                            <strong>EXCLUSIONES:</strong>
                            <br>
                            Si el asegurado participa como conductor o acompañante en competencias de automóviles, motocicletas, lanchas de motor o avioneta, prácticas de paracaídas y Alpinismo. Si el asegurado realiza operaciones o viajes submarinos o en transportes aéreos   no autorizados para transporte de pasajeros;
                            <br>
                            Guerra declarada o no, guerra civil, revolución inmolación, actos de enemigos extranjeros, hostilidad u operaciones bélicas, insurrección, guerrilla, sublevación, rebelión, sedición, motín, huelgas u otros hechos que las leyes califiquen como delitos contra la seguridad del estado.
                            <br>
                            Enfermedades preexistentes conocidas, entendiéndose como tales, aquellas enfermedades, lesiones, condición o síntoma congénito o no que se haya originado con anterioridad a la contratación del seguro y por la cual el prestatario haya recibido tratamiento médico o le hayan recomendado exámenes para diagnóstico o si se estuvo medicado.
                            <br>
                            Suicidio en el primer año;
                            <br>
                            <strong>TASA MENSUAL PORCENTUAL Y FORMA DE PAGO: (*)</strong>
                            <br>
                            Tasa por asegurado por Operación/ Préstamo /contingente y por asegurado Línea Máxima en Bs. y USD                                     {{ $header->rate }} % Mensual
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
                            Razón Social: Sudamericana Corredores y Asesores de Seguros SRL
                            <br>
                            <strong>COMPAÑÍA ASEGURADORA:</strong>
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 50%; margin-bottom: 2px;">
                                <tr>
                                    <td style="width: 50%; ">
                                        Razón Social: NACIONAL SEGUROS VIDA Y SALUD S.A.          <br>			
                                        Dirección Oficina Central (Santa Cruz): Av. Santa Cruz No. 333 esq. C/Jauru, 2do. Anillo Facsímil 	<br>
                                        Oficina Cochabamba: Av. América Este Nº 397 Acera Norte, casi esq. Plazuela 4 de Noviembre     	<br>
                                        Oficina La Paz:  Av. Costanera entre calles 8 y 9 zona de Calacoto 				<br> 
                                        Oficina Sucre:  Calle Bustillos Nº 154  Zona Central    				<br>
                                        Oficina Trinidad: Av. 18 de Noviembre Nº 142 , 					   <br>
                                        Oficina Tarija Calle Ingavi Nº 886 esq. Sevilla					

                                    </td>
                                    <td style="width: 50%; ">
                                        Teléfono Oficina Central: 591-3-3716262
                                        <br>
                                        Oficina Central: 591-3- 3337969
                                        <br>
                                        Teléfono: 4457100   Fax:  4457104
                                        <br>
                                        Teléfono:  2610950  -
                                        <br>
                                        Teléfono:  6425196  Fax:  6425197
                                        <br>
                                        Teléfono:  4634109  Fax:  4634112
                                        <br>
                                        Teléfono: 6654434
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <strong>
                                La cobertura de la póliza se activará a partir del momento del desembolso del préstamo ó de la habilitación de la tarjeta de crédito ó cartera contingente y al momento de la aceptación por parte de la compañía para los casos en los que correspondan los requisitos médicos. En caso el (los) prestatario (s) no cumpla(n) con los requisitos mínimos establecidos en la póliza, la co bertura se limitará a muerte e invalidez por accidente.
                                <br>
                                (*)     Tasas variables sujetas a las renovaciones que realice el Banco con la Aseguradora.
                                <br>
                                (**)   Sujeta a ser contratada de forma complementaria a la cobertura de muerte natural o accidental de Desgravamen Hipotecario
                                <br>
                                (***) La Tasa se aplica una vez cumplidos los requisitos de asegurabilidad y luego de haberse solo otorgado la cobertura de muerte accidental o la cobertura de muerte e invalidez accidental posterior a la
                                <br>
                                evaluación médica por parte de la Compañía
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
                                        <img src="{{ base_path('public/images/oscar.png') }}" width="100">
                                    </td>
                                    <td style="width: 20%">
                                    </td>
                                    <td style="width: 30%; text-align: center;">
                                        <img src="{{ base_path('public/images/elena.png') }}" width="100">
                                    </td>
                                    <td style="width: 10%">
                                    </td>
                                </tr>
                            </table>
                            
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
