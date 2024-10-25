@include('partials.tools_modal_de',array('type'=>$type,'idHeader'=>encode($idHeader),'aux'=>$aux))
<div style="width: 100%; font-weight: normal; font-size: 12px; font-family: Arial, Helvetica, sans-serif;
     color: #000000; ">
    
    @include('partials.logos_modal',array('image1'=>$retailer->image,'images'=>$retailerProduct))
    
    <div style="width: 100%; border: 0px solid #FFFF00; text-align:center;">
        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
            <tr>
                <td style="width:34%;" align="left">SLIP DE COTIZACIÓN<br/>{{ $cli->quote_number }}</td>
                <td style="width:32%;"></td>
                <td style="width:34%;" align="right">Cotización válida hasta el: {{ date('d-m-Y',strtotime('+2 days', strtotime($cli->created_at))) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="width:100%;" align="center">
                    DECLARACION JURADA DE SALUD<br/>
                    SOLICITUD DE SEGURO DE DESGRAVAMEN HIPOTECARIO
                </td>
            </tr>
        </table>
    </div>
    <div style="width: 100%; border: 0px solid #FFFF00; text-align:center;">
        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
            <tr>
                <td colspan="2" style="width:100%; height: 20px;" align="left">
                    Datos de la solicitud de Crédito
                </td>
            </tr>
            <tr><td colspan="2" style="width:100%;">&nbsp;</td></tr>
            <tr style="background:#E5E5E5;">
                <td style="width:50%; text-align:right; height: 20px;"><b>Tipo Cobertura:</b></td>
                <td style="width:50%; text-align: left;"> tipo cobertura </td>
            </tr>
            <tr style="background:#D5D5D5;">
                <td style="width:50%; text-align:right; height: 20px;">
                    <b>Monto Actual Solicitado: </b>
                </td>
                <td style="width:50%; text-align: left;"> {{ $cli->amount_requested }}  {{ $header->currency }}</td>
            </tr>
            <tr style="background:#E5E5E5;">
                <td style="width:50%; text-align:right; height: 20px;">
                    <b>Plazo del Presente Crédito:</b>
                </td>
                <td style="width:50%; text-align: left;">
                    {{ $cli->term }}  {{ $cli->type_term == 'D'? 'Días':$cli->type_term == 'M'? 'Meses':$cli->type_term == 'Y'? 'Años':'' }}
                </td>
            </tr>
            <tr style="background:#D5D5D5;">
                <td style="width:50%; text-align:right; height: 20px;"><b>Producto: </b></td>
                <td style="width:50%; text-align: left;">{{ $cli->coverage->name }}</td>
            </tr>
        </table>
    </div><br>

    @var $sum=1
    @foreach($cli->details as $titular)
    <div style="width: 100%; border: 0px solid #FFFF00; text-align:center;">
        <h2 style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0;
            font-weight: bold; font-size: 80%;">Titular {{ $sum }}</h2>
        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
            <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                <td style="width:25%; text-align:center; font-weight:bold;">Apellido Paterno</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Apellido Materno</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Nombres</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Apellido de Casada</td>
            </tr>
            <tr>
                <td style="width:25%; text-align:center;">{{ $titular->client->last_name }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->mother_last_name }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->first_name }}</td>
                <td style="width:25%; text-align:center;"></td>
            </tr>
            <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                <td style="width:25%; text-align:center; font-weight:bold;">Lugar de Nacimiento</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Pais</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Fecha de Nacimiento</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Lugar de Residencia</td>
            </tr>
            <tr>
                <td style="width:25%; text-align:center;">{{ $titular->client->place_residence }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->country }}</td>
                <td style="width:25%; text-align:center;">{{ date('d-m-Y', strtotime($titular->client->birthdate)) }}</td>
                <td style="width:25%; text-align:center;"></td>
            </tr>
            <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                <td style="width:25%; text-align:center; font-weight:bold;">
                    Documento de Identidad o Pasaporte
                </td>
                <td style="width:25%; text-align:center; font-weight:bold;">Edad</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Peso (kg)</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Estatura (cm)</td>
            </tr>
            <tr>
                <td style="width:25%; text-align:center;">{{ $titular->client->dni }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->age }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->weight }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->height }}</td>
            </tr>
            <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">
                    Dirección del Domicilio
                </td>
                <td style="width:25%; text-align:center; font-weight:bold;">Tel. Domicilio</td>
                <td style="width:25%; text-align:center; font-weight:bold;">Tel. Oficina</td>
            </tr>
            <tr>
                <td colspan="2" style="width:50%; text-align:center;">{{ $titular->client->home_address }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->phone_number_home }}</td>
                <td style="width:25%; text-align:center;">{{ $titular->client->phone_number_office }}</td>
            </tr>
            <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">Ocupación</td>
                <td colspan="2" style="width:50%; text-align:center; font-weight:bold;">Descripción</td>
            </tr>
            <tr>
                <td colspan="2" style="width:50%; text-align:center;">sin valor</td>
                <td colspan="2" style="width:50%; text-align:center;">{{ $titular->client->occupation_description }}</td>
            </tr>
        </table>
        <h2 style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0;
            font-weight: bold; font-size: 80%;">Cuestionario</h2>
        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
            @foreach(json_decode($titular->response->response) as $question)
            <tr>
                <td style="width: 5%; text-align: left;">{{ $question->id }}</td>
                <td style="width: 90%; text-align: left;">
                    {{ $question->question }}
                </td>
                <td style="width: 5%; text-align: right;">
                    {{ $question->response == 0 ?'NO': 'SI'}}
                </td>
            </tr>
            @endforeach
        </table><br>
        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
            <tr>
                
                @if($titular->resQuestion == 1)
                    <td style="width: 100%; border: 1px solid #3B6E22; background: red; color:#ffffff;
                        height: 20px; padding: 5px">
                        No cumple con la(s) pregunta(s) del cuestionario<br />
                        <strong>Nota: </strong> Al no cumplir con una o mas preguntas del custionario del presente slip, 
                        la compañ&iacute;a de segurso solicitar&aacute; ex&aacute;menes medicos para la autorizaci&oacute;n de 
                        aprobaci&oacute;n del seguro o en su defecto podr&aacute; declinar la misma.
                    </td>
                @else
                    <td style="width: 100%; border: 1px solid #3B6E22; background: #6AA74F; color:#ffffff;
                        height: 20px;">
                        Cumple con las preguntas del cuestionario
                    </td>
                @endif
            </tr>
            <tr>
                <td style="width: 100%;">
                    <h2 style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0;
                        font-weight: bold; font-size: 100%;">Indice de Masa Corporal</h2>
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 100%;">
                        <tr>
                            <td style="width: 30%;">
                                {{ $titular->client->imc_status }}
                            </td>
                            <td style="width: 30%;">
                                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;
                                       font-size: 100%;">
                                    <tr>
                                        <td style="width: 100%; color:#ffffff; background:#0075AA;
                                            height: 20px;" colspan="2">
                                            Datos
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 70%; height: 20px;">
                                            Estatura
                                        </td>
                                        <td style="width: 30%;">
                                            {{ $titular->client->height }} cm
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 70%; height: 20px;">
                                            Peso
                                        </td>
                                        <td style="width: 30%;">
                                             {{ $titular->client->weight }} kg
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 40%;"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                @if($titular->client->imc == 1)
                    <td style="width: 100%; border: 1px solid #3B6E22; background: red; color:#ffffff;
                        height: 20px; padding: 5px">
                        <strong>Nota: </strong> Al no cumplir con el peso y la estatura adecuados, la compañ&iacute;a de seguros solicitar&aacute; 
                        ex&aacute;menes m&eacute;dicos para la autorizaci&oacute;n de aprobaci&oacute;n del seguro o en su defecto podra declinar la misma.
                    </td>
                @else
                    <td style="width: 100%; border: 1px solid #3B6E22; background: #6AA74F; color:#ffffff;
                        height: 20px;">
                        Cumple con la estatura y peso adecuado.
                    </td>
                @endif
                
            </tr>
        </table>
    </div><br>
    @var $sum++
    @endforeach


    <div style="width: 100%; border: 0px solid #FFFF00; text-align:justify; font-size: 80%;">
        Declaro haber contestado con total veracidad, m&aacute;xima buena fe a todas las preguntas del
        presente cuestionario y no haber omitido u ocultado hechos y/o circunstancias que hubieran
        podido influir en la celebracion del Contrato de Seguro. Relevo expresamente del
        secreto profesional y legal, a cualquier m&eacute;dico que me hubiese asistido y/o tratado de
        dolencias y le autorizo a revelar a RAUL FLORES ROJAS Todos los datos y antecedentes
        patol&oacute;gicos que pudiera tener o de los que hubiera adquirido conocimiento al prestarme
        sus servicios. Entiendo que de presentarse alguna eventualidad contemplada bajo la P&oacute;liza
        de Seguro como conscuencia de alguna enfermedad existente a la fecha de la firma de este
        documento o cuando haya alcanzado la edad l&iacute;mite estipulada en la P&oacute;liza,
        la Compa&ntilde;ia Aseguradora quedara liberada de toda responsabilidad en lo que respecta a mi
        Seguro. Finalmente, declaro conocer en su totalidad lo estipulado en mi P&oacute;liza de Seguro.
        <br/><br/>
        <b>CONTRATANTE: </b>FUNDACION SARTAWI<br/>
        <b>ACTIVIDAD GENERAL: </b>{{ $cli->details->first()->client->occupation_description }}<br/><br/>
        <b>RIESGOS CUBIERTOS:</b><br/>
        <ol style="margin: 0 0 0 20px; padding: 0; list-style-type: lower-alpha;">
            <li>
                <b>Muerte por cualquier Causa</b>, que no est&eacute; excluida en el Condicionado
                General de la P&oacute;liza incluido el suicidio despu&eacute;s de 2 a&ntilde;os de
                vigencia ininterrumpida de la cobertura individual del seguro.
            </li>
            <li>
                <b>Pago anticipado del capital asegurado en caso de invalidez total y permanente por
                    accidente o enfermedad</b>, en forma irreversible por lo menos en un 65% seg&uacute;n
                el manual de normas autorizado por la APS,  en actual vigencia.
            </li>
            <li>
                <b>Gastos de Sepelio</b>, son los  gastos que demanden los herederos legales o nominados
                por el Sepelio de un Asegurado (Titular o Conyugue), como consecuencia del fallecimiento
                por una enfermedad o un accidente cubierto para el Titular.
            </li>
        </ol><br/>
        <b>CAPITALES ASEGURADOS:</b><br/>
        <ol style="margin: 0 0 0 20px; padding: 0; list-style-type:lower-alpha;">
            <li> Saldo deudor.</li>
            <li> USD 300.00</li>
        </ol><br/>
        <b>RIESGOS EXCLUIDOS:</b> La Compa&ntilde;&iacute;a no cubre y esta eximida de toda responsabilidad
        en caso de fallecimiento del asegurado en los siguientes casos:<br/>
        <ol style="margin: 0 0 0 20px; padding: 0; list-style-type:lower-alpha;">
            <li>
                Si el asegurado participa como conductor o acompa&ntilde;ante en competencias de
                autom&oacute;viles, motocicletas, lanchas de motor o avionetas, practicas de
                paraca&iacute;das.
            </li>
            <li>
                Si el asegurado realiza operaciones o viajes submarinos o en transportes a&eacute;reos
                no autorizados para transporte de pasajeros, vuelos no regulares.
            </li>
            <li>
                Enfermedades pre-existentes conocidas al inicio del seguro o enfermedad cong&eacute;nita,
                para cr&eacute;ditos mayores a USD. 5,001.00 o 35,001.00
            </li>
            <li>
                SIDA/HIV para siniestros a partir de USD. 5,001.00 o Bs. 35,001.00
            </li>
            <li>
                Guerra, invasi&oacute;n, revoluci&oacute;n, sublevaci&oacute;n, mot&iacute;n o hechos
                que las leyes clasifiquen como delitos contra la seguridad del Estado, siempre y cuando
                el asegurado no participe activamente.
            </li>
            <li>
                Suicidio realizado por el asegurado dentro de 2 a&ntilde;os de vigencia ininterrumpida
                de su cobertura. En consecuencia este riesgo quedara cubierto a partir del primer
                d&iacute;a del tercer a&ntilde;o de vigencia para cada operaci&oacute;n asegurada.
            </li>
            <li>
                Fisi&oacute;n o fusi&oacute;n nuclear
            </li>
        </ol><br/>
        <b>EDAD DE ADMISI&Oacute;N Y PERMANENCIA:</b><br/>
        <b>Para muerte:</b><br/>
        M&iacute;nima 18 a&ntilde;os<br/>
        M&aacute;xima 70 a&ntilde;os (hasta cumplir los 71 a&ntilde;os)<br/>
        <b>Permanencia:</b> 75 a&ntilde;os inclusive hasta cumplir los 76 a&ntilde;os<br/><br/>
        <b>Para invalidez:</b><br/>
        Minima 18 a&ntilde;os<br/>
        Maxima 64 a&ntilde;os (hasta cumplir 65 a&ntilde;os)<br/>
        <b>Permanencia:</b> 70 a&ntilde;os inclusive hasta cumplir los 71 a&ntilde;os<br/><br/>
        <h2 style="width: auto;	height: auto; text-align: left; margin: 7px 0; padding: 0;
            font-weight: bold; font-size: 100%;">Tasa Mensual</h2>
        <table cellpadding="0" cellspacing="0" border="0" style="width: 70%; font-size: 100%;"
               align="center">
            <tr style="font-weight:bold; text-align:center; color:#FFF; background:#0075AA;">
                <td style="width: 30%; height: 20px;">NOMBRE</td>
                <td style="width: 30%; height: 20px;">VALOR ASEGURADO</td>
                <td style="width: 10%; height: 20px;">TASA FINAL</td>
            </tr>
            @foreach($cli->details as $titular)
            <tr>
                <td style="width: 30%;">{{ $titular->client->full_name }}</td>
                <td style="width: 30%;">{{ $cli->amount_requested }}  {{ $header->currency }}</td>
                <td style="width: 10%;">{{ $cli->total_rate }}%</td>
            </tr>
            @endforeach
        </table><br>
        <b>PRIMA:</b> De acuerdo a declaraciones mensuales del contratante, por mes vencido<br/>
        <b>FORMA DE PAGO:</b> Contado a trav&eacute;s de FUNDACION SARTAWI<br/>
        <b>BENEFICIARIO:</b> FUNDACION SARTAWI T&Iacute;TULO ONEROSO y/o El Tomador del seguro<br/><br/>
        <b>OBSERVACIONES:</b> Las primas del seguro no constituyen hecho generador de tributo seg&uacute;n
        el Art. No. 54 de la Ley de Seguros 1883 del 25 de Junio de 1998 y a la Resoluci&oacute;n
        Ministerial Nro. 880 del 28 de Junio de 1999. Autorizo a la compa&ntilde;&iacute;a mi reporte a la
        Central de Riesgos del Mercado de Seguros, acorde a las normativas reglamentarias de la Autoridad
        de Fiscalizaci&oacute;n y Control de Pensiones y Seguros.<br/><br/>
        <b>INICIO DE LA COBERTURA:</b> La cobertura de esta P&oacute;liza para cada Asegurado aceptado,
        comenzar&aacute; a partir del momento del desembolso de su cr&eacute;dito por parte de
        <b>FUNDACI&Oacute;N SARTAWI</b>, previo cumplimiento de lo establecido en los "Requisitos de
        Asegurabilidad".<br/><br/>
        <b>AVISO DE SINIESTRO:</b> En caso de fallecimiento o invalidez total y permanente del Asegurado,
        el tomador, tan pronto y a m&aacute;s tardar dentro de los 90 d&iacute;as calendario siguientes
        de tener conocimiento del siniestro, debe comunicar el mismo a la Compa&ntilde;&iacute;a,
        salvo fuerza mayor o impedimento justificado, caso contrario la Compa&ntilde;&iacute;a se libera
        de cualquier responsabilidad indemnizatoria por extemporaneidad.<br/><br/>
        <b>PLAZO PARA PAGO DE SINIESTRO:</b> El plazo para el pago de siniestros por sepelio ser&aacute;
        realizado dentro de 2 d&iacute;as h&aacute;biles de recibidos todos los documentos requeridos por
        la compa&ntilde;&iacute;a.
        para el pago de siniestros por las dem&aacute;s coberturas ser&aacute; dentro de los 10
        d&iacute;as de presentados todos los documentos.<br/><br/>
        <b>PARA SINIESTROS HASTA USD 5.000:</b>
        <ol style="margin: 0 0 0 20px; padding: 0; list-style-type:lower-alpha;
            list-style-type:lower-alpha;">
            <li> Original de la Declaraci&oacute;n Jurada de Salud.</li>
            <li>
                Certificado de Defunci&oacute;n, (para el &aacute;rea rural podr&aacute; presentar un
                certificado de cualquier autoridad local del pueblo o localidad, dandofe del fallecimiento
                del asegurado y la certificaci&oacute;n del jefe de agencia).
            </li>
            <li>
                Fotocopia de C.I. y/o fotocopia de Certificado de Nacimiento y/o Carnet de Identidad
                RUN y/o libreta de servicio militar.
            </li>
            <li>
                Estado de cuenta saldo deudor.
            </li>
            <li>
                <b>Para el caso de Invalidez:</b> Certificado INSO (Instituto Nacional de Salud Ocupacional)
                o en su defecto de otra instituci&oacute;n que est&eacute; debidamente autorizada por la
                Autoridad Competente, la cual determine el grado de invalidez.
            </li>
        </ol><br/>
        <b>PARA SINIESTROS DE USD 5.001 EN ADELANTE:</b>
        <ol style="margin: 0 0 0 20px; padding: 0; list-style-type:lower-alpha;">
            <li>Original de la Declaraci&oacute;n Jurada de Salud</li>
            <li>
                Certificado de Defunci&oacute;n, (para el &aacute;rea rural podr&aacute; presentar
                un certificado de cualquier autoridad local del pueblo o localidad, dando fe del
                fallecimiento del asegurado y la certificaci&oacute;n del jefe de agencia).
            </li>
            <li>
                Fotocopia de C.I. y/o fotocopia de Certificado de Nacimiento y/o Carnet de Identidad
                RUN y/o libreta de servicio militar.
            </li>
            <li>Estado de cuenta saldo deudor.</li>
            <li>Historia Clinica si existiera.</li>
            <li>
                <b>Para el caso de Invalidez:</b> Certificado INSO (Instituto Nacional de
                Salud Ocupacional) o en su defecto de otra instituci&oacute;n que est&eacute;
                debidamente autorizada por la Autoridad Competente, la cual determine el
                grado de invalidez.
            </li>
        </ol><br/>
        <b>SEPELIO:</b>
        <ol style="margin: 0 0 0 20px; padding: 0; list-style-type:lower-alpha;">
            <li>
                Fotocopia del Carnet de Identidad del Asegurado, y/o Fotocopia de Certificado
                de nacimiento y/o Carnet de identidad RUN y/o libreta del servicio militar.
            </li>
            <li>
                Fotocopia de carnet de identidad del Beneficiario y/o Fotocopia de Certificado
                de nacimiento y/o Carnet de identidad RUN y/o libreta de servicio militar.
            </li>
            <li>
                Certificado de Defunci&oacute;n, (para el &aacute;rea rural podr&aacute; presentar
                un certificado de cualquier autoridad local del pueblo o localidad, dandofe del
                fallecimiento del asegurado y la certificaci&oacute;n del jefe de agencia).
            </li>
            <li>
                Declaratoria de Beneficiarios o declaratoria de herederos en caso de no existir
                la nominacion de los mismos.
            </li>
            <li>Carta de los beneficiarios solicitando el beneficio.</li>
        </ol><br/>
        Todo lo que no est&eacute; previsto por el Certificado de Cobertura Individual, se sujetar&aacute;
        a lo establecido en las Condiciones Particulares, Condiciones Generales y dem&aacute;s documentos
        Anexos a la presente P&oacute;liza de Seguro de Desgravamen Hipotecario en Grupo, el C&oacute;digo
        de Comercio, la Ley de Seguros y por las disposiciones legales vigentes en la materia.<br/><br><br>
        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 60%; height: auto; font-size: 80%; font-family: Arial;">
            <tr>
                @foreach($cli->details as $titular)
                <td style="width:30%;" align="center">{{ $titular->client->full_name }}</td>
                @endforeach

                <td style="width:30%;" align="center">{{ date('d-m-Y', strtotime($cli->created_at)) }}</td>
            </tr>
            <tr>
                @var $sum=1
                @foreach($cli->details as $titular)
                <td style="width:30%;" align="center"><b>Titular {{ $sum }}</b></td>
                @var $sum++
                @endforeach

                <td style="width:30%;" align="center"><b>Fecha Actual</b></td>
            </tr>
        </table>
    </div>
</div>
