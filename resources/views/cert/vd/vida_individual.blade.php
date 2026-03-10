@var $tomador = $header->details->where('holder',1)->first()
@var $titular = $header->details->first()
@var $plans = json_decode($header->plan->plan, true)

@if($header->plan->name == 'Plan A')
    @var $plan = 'vda'
@elseif($header->plan->name == 'Plan B')
    @var $plan = 'vdb'
@elseif($header->plan->name == 'Plan C')
    @var $plan = 'vdc'
@elseif($header->plan->name == 'Plan D')
    @var $plan = 'vdd'
@elseif($header->plan->name == 'Plan V')
    @var $plan = 'vdz'
@endif
<div style="width: 720px; font-weight: normal; font-size: 9px; font-family: Arial, Helvetica, sans-serif;
     color: #000000; border: 0px solid #FFFF00; text-align:justify;">
    <table cellpadding="0" cellspacing="0" border="0" style="width:100%; font-weight:bold;">
        <tr>
            <td style="width:50%;">
                <img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" width="100">
                &nbsp;
            </td>
            <td style="width:50%; text-align:right;" valign="top">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td style="width:50%;">
                &nbsp;
            </td>
            <td style="width:50%; text-align:right;" valign="top">
                No. {{$header->issue_number}}
                &nbsp;                        
            </td>
        </tr>
    </table><br>

    <div style="text-align:center; font-weight:bold;">
        PÓLIZA DE SEGURO DE VIDA EN GRUPO TEMPORAL ANUAL RENOVABLE AUTOMÁTICAMENTE DECLARACIÓN DE SALUD
    </div><br><br>

    <table
        cellpadding="3" cellspacing="0"
        style="width: 100%; height: auto; font-family: Arial;">
        <tr>
            <td colspan="2">
                &nbsp;
            </td>
            <td style="width:25%; border:1px solid #000000;">
                <b>Lugar:</b> {{$header->user->city->name}}
            </td>
            <td style="width:25%; border:1px solid #000000;">
                <b>Fecha:</b> {{date('d/m/Y', strtotime($header->date_issue))}}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background:#255177; color:white; border:1px solid #000000;">
                <b>I. DATOS PERSONALES</b>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border:1px solid #000000;">
                <b>Nombres:</b> {{$titular->client->first_name}}
            </td>
            <td colspan="2" style="border:1px solid #000000;">
                <b>Apellidos:</b> {{$titular->client->last_name}} {{$titular->client->mother_last_name}}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border:1px solid #000000;">
                <b>Tipo de Documento de identidad:</b> <br><br>
                <table cellpadding="0" cellspacing="0" border="0" style="width:100%; text-align:center;">
                    <tr>
                        <td style="width:10%;"><b>C.I.</b></td>
                        <td style="width:5%; border:1px solid #000000;">
                            {{$titular->client->document_type == 'CI'?'X':' '}}
                        </td>
                        <td style="width:35%;">&nbsp;</td>
                        <td style="width:10%;"><b>Otro</b></td>
                        <td style="width:5%; border:1px solid #000000;">
                            {{$titular->client->document_type == 'CI'?' ':'X'}}
                        </td>
                        <td style="width:35%;">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td colspan="2" style="border:1px solid #000000;">
                <b>Número de Documento de Identidad:</b><br><br> 
                    {{$titular->client->full_dni}}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background:#255177; color:white; border:1px solid #000000;">
                <b>II. DECLARACIÓN DE SALUD</b>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="border:1px solid #000000;">
                @var $resp = count(json_decode($titular->response->response, true));
                @var $nro = '&nbsp;'
                @var $det = '&nbsp;'
                @var $est = '&nbsp;'
                @foreach(json_decode($titular->response->response) as $key => $val)                    
                    @if($val->response)
                        @var $nro = 1
                        @var $det = $val->response_specification
                        @var $est = ''
                    @endif                  
                    <table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
                        <tr>
                            <td style="width:80%;"><b>{{strtoupper( $val->question)}}</b></td>
                            <td style="width:5%;">SI</td>
                            <td style="width:5%;"><div style="border: .5px solid black;width:15px; text-align:center;">{{$val->response?'&nbsp;X&nbsp;':'&nbsp;&nbsp;&nbsp;'}}</div></td>
                            <td style="width:5%;">NO</td>
                            <td style="width:5%;"><div style="border: .5px solid black;width:15px; text-align:center;">{{! $val->response?'&nbsp;X&nbsp;':'&nbsp;&nbsp;&nbsp;'}}</div></td>
                        </tr>
                    </table>
                @endforeach
            </td>
        </tr>
        <tr>
            <td style="width:25%; border:1px solid #000000; text-align:center;"><b>Enfermedad</b></td>
            <td style="width:25%; border:1px solid #000000; text-align:center;"><b>Fecha de diagnostico</b></td>
            <td style="width:25%; border:1px solid #000000; text-align:center;"><b>Estado actual</b></td>
            <td style="width:25%; border:1px solid #000000; text-align:center;"><b>Observaciones</b></td>
        </tr>
        <tr>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
            <td style="width:25%; border:1px solid #000000;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:justify; border:1px solid #000000;">
                Al firmar la presente Declaración de Salud, declaro que las respuestas que he consignado en ella son verdaderas y completas y que es de mi conocimiento que cualquier declaración falsa, inexacta, omitida, incompleta u oculta puede implicar la anulación del Seguro, salvando lo establecido en los Artículos 995 y 1138 del Código de Comercio.
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center; border:1px solid #000000;">
                <br><br><br><br><br><br><br><br><br><br>
                FIRMA
                <br><br>
            </td>
        </tr>
    </table><br>
     Registrado ante la Autoridad de Fiscalización y Control de Pensiones y Seguros (APS) con el Código de Registro 209-934619-2013 06 004 3007
    
    @if($header->issued && ! $header->canceled)
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        
        <div style="margin: 2mm 1mm 2mm 2mm; text-align: justify; font-size: 9px; font-family: Arial, Helvetica, sans-serif;">
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; font-weight:bold;">
                <tr>
                    <td style="width:100%; text-align:right;" valign="top">
                        <img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" width="100">
                        &nbsp;                        
                    </td>
                </tr>
                <tr>
                    <td style="width:100%; text-align:right;">
                        No. {{ $header->issue_number }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <b>POLIZA DE SEGURO DE VIDA EN GRUPO TEMPORAL ANUAL RENOVABLE AUTOMÁTICAMENTE<br>
                            CERTIFICADO DE COBERTURA INDIVIDUAL-C</b><br>
                            Registrada en Autoridad de Fiscalización y Control de Pensiones y Seguros (APS)<br>
                            <b>Resolución Administrativa APS/DS/N° 468-2018 con Código de Registro N° 209-934619-2013 06 004 4004</b><br>
                            Póliza N° CRS-MCBS-065

                    </td>
                </tr>
            </table><br><br>

            <b>Lugar y Fecha:</b> <u>&nbsp;{{$header->user->city->name}}&nbsp;</u>, 
            <u>&nbsp;{{date('d', strtotime($header->date_issue))}}&nbsp;</u>&nbsp;/&nbsp;
            <u>&nbsp;{{date('m', strtotime($header->date_issue))}}&nbsp;</u>&nbsp;/&nbsp;
            <u>&nbsp;{{date('Y', strtotime($header->date_issue))}}&nbsp;</u><br>

            <b>TITULAR</b><br>
            Nombre del Asegurado: <u>{{$titular->client->full_name}}</u>
            N° de CI: <u>{{$titular->client->dni}}</u>
            Ext. <u>{{$titular->client->extension}}</u><br>
            Fecha de Nacimiento:
            <u>&nbsp;{{date('d', strtotime($titular->client->birthdate))}}&nbsp;</u>&nbsp;/&nbsp;
            <u>&nbsp;{{date('m', strtotime($titular->client->birthdate))}}&nbsp;</u>&nbsp;/&nbsp;
            <u>&nbsp;{{date('Y', strtotime($titular->client->birthdate))}}&nbsp;</u><br>

            <b>CREDISEGURO S.A. SEGUROS PERSONALES</b> domiciliada en Av. Hernando Siles esq. Calle 10, Torre Empresarial ESIMSA, piso 9, zona Obrajes de la ciudad de La Paz, teléfono 2175900, (LA COMPAÑÍA), certifica que la persona indicada en el presente documento se encuentra asegurada bajo la presente Póliza (ASEGURADO), contratada por COOPERATIVA DE AHORRO Y CREDITO SOCIETARIA “UNION SANTIAGO DE MACHACA USAMA R.L.<br>
            Intermediada por SUDAMERICANA SRL CORREDORES Y ASESORES DE SEGUROS, Avenida Arce, pasaje Cordero N°163 Zona San Jorge (CORREDOR DE SEGUROS).<br>
            La cobertura se inicia con el pago de la prima, siempre que se haya cumplido con los requisitos de asegurabilidad y cuente con la autorización de LA COMPAÑÍA.<br><br>
            
            <b>COBERTURAS Y CAPITAL ASEGURADO:</b>
            
            <table cellpadding="3" cellspacing="0" border="1" style="width:100%;">
                <tr>
                    <td style="width:50%;"><b>COBERTURA PRINCIPAL</b></td>
                    <td style="width:10%;"><b>HASTA</b></td>
                    <td style="width:40%;"><b>CARENCIA</b></td>
                </tr>
                <tr>
                    <td style="width:50%;">Muerte por Cualquier Causa </td>
                    <td style="width:10%;">6.000,00</td>
                    <td style="width:40%;">30 días (no aplica para muerte accidental)</td>
                </tr>
                <tr>
                    <td style="width:50%;"><b>COBERTURAS ADICIONALES</b> </td>
                    <td style="width:10%;"><b>HASTA</b></td>
                    <td style="width:40%;"><b>&nbsp;</b></td>
                </tr>
                <tr>
                    <td style="width:50%;">Gastos de Sepelio</td>
                    <td style="width:10%;">2.000,00</td>
                    <td style="width:40%;">N/A</td>
                </tr>
            </table><br>

            <b>PROCEDIMIENTO EN CASO DE SINIESTRO:</b><br>
            La Compañía debe pronunciarse sobre el derecho del (los) Beneficiario(s) dentro de los 30 (treinta) días de recibidos todos los informes, evidencias, documentos y/o requerimientos adicionales acerca de los hechos y circunstancias del siniestro, las evidencias conducentes a la determinación de la causa, identidad de las personas o intereses asegurados y cuantía de los daños, de acuerdo a lo establecido en el Artículo 1031 del Código de Comercio, se dejará constancia escrita de la fecha de recepción de la información y evidencias a efectos del cómputo del plazo. En caso de demora u omisión del Asegurado o Beneficiario en proporcionar la información y evidencias sobre el siniestro, el término señalado no corre hasta el cumplimiento de estas obligaciones. El silencio de la Compañía, vencido el término para pronunciarse, determina la aceptación del reclamo. La Compañía procederá al pago del Beneficio en el plazo máximo de 15 (quince) días posteriores al aviso del siniestro o tan pronto sean llenados los requerimientos señalados en el artículo 1031 del Código de Comercio.<br><br>
            <b>PRIMA Y FORMA DE PAGO:</b><br>
            <b>Prima:</b> De acuerdo a las tasas/primas establecidas para cada ASEGURADO, sea individual o especial. EL TOMADOR recauda las Primas individuales de los ASEGURADOS y paga a LA COMPAÑÍA la Prima de toda la cartera sujeta a cobertura.<br>  
            <i>Nota.- El ASEGURADO contará con cobertura, mientras sus cuotas se encuentren pagadas.</i><br>
            <b>Forma de Pago:</b> Anual Bs. 165,00 (al contado por adelantado)<br><br>
            <b>DECLARACIONES Y AUTORIZACIONES:</b><br>
            <b>Declaración:</b> El ASEGURADO declara conocer y aceptar las Condiciones del Seguro que se encuentran reflejadas en el presente certificado de cobertura.<br>
            <b>Declaración de Salud:</b> El ASEGURADO que no está sujeto al llenado de la Declaración de Salud, declara que a la fecha <b>NO TIENE CONOCIMIENTO DE ALGUNA ENFERMEDAD PREXISTENTE</b> y goza de buena salud.<br>
            <b>Autorización expresa:</b> El ASEGURADO, autoriza a La Compañía su reporte a la Central de Riesgos del Mercado de Seguros acorde a las normativas reglamentarias de la Autoridad de Fiscalización y Control de Pensiones y Seguros vigente. Asimismo, El ASEGURADO, autoriza(n) expresamente a LA COMPAÑÍA a solicitar, obtener y dar información respecto a sus antecedentes financieros, de seguros y de salud, a través de ellos o terceras personas, adicionalmente a solicitar y/u obtener pruebas y/o resultados de VIH/SIDA.<br>
            Todos los beneficios a los cuales el ASEGURADO tiene derecho se sujetan a lo estipulado en las Condiciones Generales, Particulares y/o Adicionales de la Póliza de Seguro de Vida en Grupo Temporal Anual Renovable Automáticamente de la cual el presente certificado forma parte integrante. El ASEGURADO podrá requerir a LA COMPAÑÍA o a EL TOMADOR cualquier información adicional que considere necesaria.<br>
            El ASEGURADO acepta la presentación, con calidad de Declaración Jurada, de la documentación de respaldo que solicitará CREDISEGURO S.A. SEGUROS PERSONALES misma que será requerida antes y durante la vigencia y en virtud de la obligación normativa regulatoria que éste mantiene respecto a los controles e informes que realiza por instrucción de la Unidad de Investigaciones Financieras u otras entidades competentes de acuerdo a normativa vigente.<br><br>
            <b>DISCREPANCIAS EN LA POLIZA:</b> Si el Tomador/Contratante y/o padre/madre de familia y/o tutor y/o ASEGURADO encuentran discrepancias entre la Póliza y lo convenido, podrán solicitar por escrito la rectificación de la misma dentro de los quince (15) días siguientes a la recepción de la misma. Si, una vez realizado el reclamo, LA COMPAÑÍA mantiene silencio o no contesta a la reclamación, se entenderá aceptada la modificación solicitada.<br><br>
            <b>CLAUSULA ARBITRAL:</b> Las controversias de hecho sobre las características técnicas del seguro, serán resueltas a través del peritaje, con base en lo establecido en las condiciones de cada Póliza. De no llegar a un acuerdo por esta vía, las controversias deberán definirse por la vía del arbitraje. Las controversias de derecho suscitadas entre las partes, referidas a la interpretación, naturaleza y alcance de cada Póliza, sus documentos complementarios y modificatorios, se resolverán definitivamente mediante Arbitraje de derecho administrado por el Centro de Conciliación y Arbitraje de la Cámara Nacional de Comercio de acuerdo con su reglamento vigente y en el marco de la Ley 708 de 25 de junio de 2015 (Ley de Conciliación y Arbitraje).<br><br>

            La Autoridad de Fiscalización y Control de Pensiones y Seguros (APS) podrá fungir como instancia de conciliación, para todo siniestro cuya cuantía no supere el monto de UFV 100.000.- (Cien mil 00/100) Unidades de Fomento de Vivienda). Si por esa vía no se llegara a un acuerdo, la APS podrá conocer y resolver la controversia por resolución administrativa debidamente motivada.<br><br>

            <b>Declaración de Beneficiarios:</b> El ASEGURADO voluntariamente señala a la(s) siguiente(s) persona(s) como beneficiario(s):
            <table cellpadding="3" cellspacing="0" border="1" style="width:100%;">
                <tr>
                    <td style="width:60%; text-align:center;"><b>Nombre Completo</b></td>    
                    <td style="width:20%; text-align:center;"><b>Parentesco</b></td>    
                    <td style="width:20%; text-align:center;"><b>% de participación de cada Beneficiario</b></td>    
                </tr>
                @foreach($titular->beneficiaries as $key => $value)
                    <tr>
                        <td style="width:60%; text-align:center;">{{$value->first_name}} {{$value->last_name}} {{$value->mother_last_name}} {{$value->married_last_name}}</td>
                        <td style="width:20%; text-align:center;">{{$value->relationship}}</td>
                        <td style="width:20%; text-align:center;">{{$value->percentage}} %</td>
                    </tr>            
                @endforeach
                @if(count($titular->beneficiaries) < 3)
                    @for($i=0; $i < (3 - count($titular->beneficiaries)); $i++)
                        <tr>
                            <td style="width:60%; text-align:center;">&nbsp;</td>
                            <td style="width:20%; text-align:center;">&nbsp;</td>
                            <td style="width:20%; text-align:center;">&nbsp;</td>
                        </tr>            
                    @endfor
                @endif
            </table>

            <page><div style="page-break-before: always;">&nbsp;</div></page>
            {{-- <table cellpadding="0" cellspacing="0" border="0" style="width:100%; font-weight:bold;">
                <tr>
                    <td style="width:100%; text-align:right;" valign="top">
                        <img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" width="100">
                        &nbsp;                        
                    </td>
                </tr>
            </table> --}}
            <b>LÍMITES DE EDAD POR COBERTURA:</b><br>
            <b>Para Muerte por cualquier causa:</b> Ingreso desde los 18 años hasta los 64 años y 364 días al momento del inicio de la Cobertura, edad límite de permanencia hasta los 65 años y 364 días.<br>
            <b>Para las coberturas Adicionales:</b> Ingreso desde los 18 años hasta los 64 años y 364 días al momento del inicio de la Cobertura, edad límite de permanencia hasta los 65 años y 364 días.<br>
            <b>EXCLUSIONES:</b><br>
            <b>Exclusiones para la Cobertura de Muerte</b><br>
            Este contrato de seguros no cubre el fallecimiento del Asegurado cuando el deceso se produjera como consecuencia de: 
            <ol type="a">
                <li><b>Estado etílico del Asegurado, con presencia de alcohol en la sangre en proporción mayor de 0.7 gramos-litro, salvo cuando el Asegurado hubiere sido sujeto pasivo en el acontecimiento que produjo su deceso.</b></li>
                <li>Enfermedad grave y/o crónica prexistente al inicio del seguro, siempre que haya sido de conocimiento del Asegurado y/o diagnosticada con anterioridad a su incorporación al seguro.</li>
                <li>Suicidio, salvo que el Asegurado haya mantenido ininterrumpidamente la cobertura por al menos dos (2) años.</li>
                <li>Viaje o vuelo en vehículo aéreo de cualquier clase, excepto como pasajero en un avión operado por una empresa de transporte aéreo comercial sobre una ruta establecida para el transporte de pasajeros sujeto a itinerario regular, entre aeropuertos debidamente establecidos y habilitados por la autoridad competente del país respectivo, sobre una ruta regular establecida para el transporte de pasajeros sujeto a itinerario.</li>
                <li>Acto criminal en el que resulte responsable el beneficiario, en cuyo caso la parte que correspondía al beneficiario responsable acrecerá la de los demás beneficiarios. A falta de todos éstos, la Suma Asegurada será entregada a los herederos del Asegurado, instituidos de acuerdo a ley, con exclusión del Beneficiario responsable del fallecimiento del Asegurado.</li>
                <li>Participación del Asegurado como sujeto activo en acto delictuoso o empresa criminal, o por aplicación legítima de la pena de muerte.</li>
                <li>Guerra declarada o no declarada (conflicto armado), civil o internacional; servicio y/o actividad militar o policial de cualquier índole, tanto en tiempo de paz como en tiempo de guerra.</li>
                <li>Actos de guerrilla, rebelión, sedición, motín, conmoción contra el orden público, terrorismo, huelga o tumulto popular, cuando el Asegurado hubiera participado como elemento activo.</li>
                <li>Participar en: inmersión submarina; montañismo; ala delta; paracaidismo; parapente; artes marciales, boxeo u otros deportes o disciplinas de peleas; equitación; carreras de caballo. Asimismo, la participación del Asegurado como conductor o acompañante, profesionalmente o como aficionado, en competencias o ensayos de velocidad o resistencia, en cualquier clase de vehículo, terrestre, acuático o aéreo, a motor o no.</li>
                <li>Participar en: actos y riesgos políticos y/o terrorismo; atentar contra la propia vida y la de terceros al manipular armas cortopunzantes y/o armas de fuego; o realizar actividades donde se pone en grave peligro la vida e integridad física de las personas.</li>
                <li>VIH/SIDA.</li>
                <li>Cuando el Asegurado esté bajo los efectos de drogas ilícitas.</li>
                <li>La participación del Asegurado en cualquier maniobra, experimento, exhibición, desafío o actividad notoriamente peligrosa, entendido por tales aquellas donde se pone en grave peligro la vida e integridad física de las personas.</li>
                <li>Epidemias declaradas como tal por las entidades gubernamentales competentes.</li>
                <li>Las producidas como consecuencia directa o indirecta de la reacción nuclear o contaminación radioactiva o química.</li>
            </ol>  

            <b>Para las coberturas Adicionales:</b> (según corresponda): <br>
            <b>Exclusiones para la cobertura de Gastos de Sepelio</b>

            <ol type="a">
                <li>Las exclusiones son las mismas que las de cobertura de muerte.</li>
            </ol>

            {{-- <page><div style="page-break-before: always;">&nbsp;</div></page>
            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; font-weight:bold;">
                <tr>
                    <td style="width:100%; text-align:right;" valign="top">
                        <img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" width="100">
                        &nbsp;                        
                    </td>
                </tr>
            </table><br> --}}
            
            <b>TERMINOS Y CONDICIONES DE ADHESIÓN:</b> El ASEGURADO declara conocer y aceptar todas las Condiciones del Seguro y se adhiere voluntariamente al mismo, así como a la posibilidad de remplazar la(s) cobertura(s) exigida(s), con otra póliza de similares características.<br><br>

            <b>REQUISITOS PARA SOLICITAR EL BENEFICIO: </b><br>
            LA COMPAÑÍA efectuará el pago que corresponda, para lo cual EL TOMADOR deberá presentar la Carta de Aviso de Siniestro, adjuntando fotocopia del Documento de Identidad o Certificado de Nacimiento y pruebas del siniestro correspondiente.<br>
            <b>Requisitos para la Cobertura Principal: </b><br>
            <b>Muerte por Cualquier Causa:</b> a) Certificado de Defunción, b) Certificado Médico Único de Defunción, c) Historial Médico en caso de muerte natural, 
            c) Comprobantes de las últimas tres remuneraciones del Asegurado o documento equivalente (Caja de Salud, AFP), d) examen toxicológico y de alcoholemia (si corresponde), 
            f) en caso de que el siniestro supere USD 10.000 o su equivalente en Bs., se deberá presentar el formulario PCC04). <br>
            <b>Requisitos para las Coberturas Adicionales: </b>
            <b>Cobertura Gastos de Sepelio:</b> Factura(s) de pago original(es) del(los) gastos incurridos para el Sepelio del Asegurado. La(s) factura(s) correspondiente(s) debe(n) ser emitidas a nombre de Crediseguro S.A. Seguros Personales con No. de NIT 191310020 y presentada(s) a la Compañía dentro del mismo mes, caso contrario, se procederá al descuento correspondiente de impuestos previo al pago de la indemnización. Asimismo, se proporcionará a la Compañía cualquier información que solicite para verificar el fallecimiento y se le permitirá realizar las indagaciones que sean necesarias para tal fin.<br><br>

            <table cellpadding="3" cellspacing="0" border="0" style="width:100%; text-align:center;">
                <tr>
                    <td style="width:10%;">&nbsp;</td>
                    <td style="width:20%;"><img src="{{ base_path('public/assets/files/CRSP-Gerente-General.jpg') }}" width="100"></td>
                    <td style="width:10%;">&nbsp;</td>
                    <td style="width:20%;"><img src="{{ base_path('public/assets/files/CRSP-Gerente-Finanzas.jpg') }}" width="100"></td>
                    <td style="width:10%;">&nbsp;</td>
                    <td style="width:10%;">&nbsp;</td>
                    <td style="width:10%;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:10%;">&nbsp;</td>
                    <td style="width:20%;">CREDISEGURO S.A.<br>Seguros Personales</td>
                    <td style="width:10%;">&nbsp;</td>
                    <td style="width:20%;">CREDISEGURO S.A.<br>Seguros Personales</td>
                    <td style="width:10%;">&nbsp;</td>
                    <td style="width:10%;">Asegurado</td>
                    <td style="width:10%;">&nbsp;</td>
                </tr>
            </table>
        </div>
    @endif
</div>