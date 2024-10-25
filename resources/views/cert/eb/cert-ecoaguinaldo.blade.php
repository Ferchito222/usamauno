@if(request()->session()->has('cashier') && $format === 'print')
    <div style="margin: 50px 20px 20px 20px; padding: 20px;">
        <div style="text-align:right; font-size:10px; font-weight:bold;">Nº {{ $header->issue_number }}</div>
        <div style="text-align:center; font-weight:bold; font-size:10px;">
            <br>
        </div>
        <div style="font-weight:bold; font-size:10px;">
            Lugar y Fecha: {{ $header->user->city->name }}, {{ $header->date_issue->format('d/m/Y') }}
            <br>
            <table style="font-size:10px; ">
                <tr>
                    <td width="120px">Nombre del Asegurado:</td>
                    <td style="border-bottom: 1px; border-color:black; width:240px;">
                        {{ $header->detail->client->full_name }}
                    </td>
                    <td width="45px">Nº de CI:</td>
                    <td style="border-bottom: 1px; border-color:black; width:50px;">
                        {{ $header->detail->client->dni }} {{ $header->detail->client->complement }} {{ $header->detail->client->extension }}
                    </td>
                    <td width="115px" style="text-align: right; padding-right: 5px;">Fecha de Nacimiento:</td>
                    <td style="border-bottom: 1px; border-color:black; width:45px;">
                        {{ $header->detail->client->birthdate->format('d/m/Y') }}
                    </td>
                    <td style="width: 85px; text-align: right;">N° operación:</td>
                    <td style="border-bottom: 1px; border-color:black; width:60px;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>
        <br>
        <div style="text-align:center;" align="center">
            <table style="font-size:10px; border-collapse: collapse;  " border="1" width="">
                <tr>
                    <td colspan="3" style="font-weight:bold;">DECLARATORIA DE BENEFICIARIOS</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold;">Señores Crediseguro</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold;">Nombro voluntariamente como Beneficiario(s) a la(s)
                        siguiente(s)
                        persona(s):
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left;">Nombre Completo</td>
                    <td>Parentesco</td>
                    <td>% de participación de cada Beneficiario</td>
                </tr>
                <tr>
                    <td style="text-align:left;">
                        {{ $header->detail->beneficiary->full_name }}
                    </td>
                    <td>
                        {{ $header->detail->beneficiary->relationship }}
                    </td>
                    <td>
                        {{ $header->detail->beneficiary->percentage_credit }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
@else
    <page backtop="50px">
        <page_header>
            <table style="width: 100%;">
                <tr>
                    <td style="width:50%; text-align:left;">
                        &nbsp;&nbsp;
                        {!! Html::image('images/ecoaguinaldo.jpg', '', ['width' => '150']) !!}
                    </td>
                    <td style="width:50%; text-align:right;">
                        {!! Html::image($header->retailerProduct->retailer->image, '', ['width' => '230']) !!}
                    </td>
                </tr>
            </table>
        </page_header>

        <div style="text-align:right; font-size:6pt; font-weight:bold;">Nº {{ $header->issue_number }}</div>
        <div style="text-align:center; font-weight:bold; font-size:6pt;">
            NOTA DE COBERTURA INDIVIDUAL - PÓLIZA DE SEGURO COBERTURA COMPLETA <br>
            POLIZA N° CRS-MCBS-004
        </div>
        <div style="font-weight:bold; font-size:6pt; text-align:left;">
            Lugar y Fecha: {{ $header->user->city->name }}, {{ $header->date_issue->format('d/m/Y') }}
            <br>
            <table style="font-size:6pt;">
                <tr>
                    <td width="80px">Nombre del Asegurado:</td>
                    <td style="border-bottom: 1px; border-color:black; width:240px;">
                        {{ $header->detail->client->full_name }}
                    </td>
                    <td width="35px">Nº de CI:</td>
                    <td style="border-bottom: 1px; border-color:black; width:50px;">
                        {{ $header->detail->client->dni }} {{ $header->detail->client->complement }} {{ $header->detail->client->extension }}
                    </td>
                    <td width="85px">Fecha de Nacimiento:</td>
                    <td style="border-bottom: 1px; border-color:black; width:45px;">
                        {{ $header->detail->client->birthdate->format('d/m/Y') }}
                    </td>
                    <td>&nbsp;N° Operación:</td>
                    <td style="border-bottom: 1px; border-color:black; width:60px;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </div>
        <br>
        <div style="text-align:center;" align="center">
            <table style="font-size:6pt; border-collapse: collapse;  " border="1" width="">
                <tr>
                    <td colspan="3" style="font-weight:bold;">DECLARATORIA DE BENEFICIARIOS</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold;">Señores Crediseguro</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight:bold;">Nombro voluntariamente como Beneficiario(s) a la(s)
                        siguiente(s) persona(s):
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left;">Nombre Completo</td>
                    <td>Parentesco</td>
                    <td>% de paricipación de cada Beneficiario</td>
                </tr>
                <tr>
                    <td style="text-align:left;">
                        {{ $header->detail->beneficiary->full_name }}
                    </td>
                    <td>
                        {{ $header->detail->beneficiary->relationship }}
                    </td>
                    <td>
                        {{ $header->detail->beneficiary->percentage_credit }}
                    </td>
                </tr>
            </table>
        </div>
        <br>
        <div style="text-align:justify; font-size:6pt;">
            <b>CREDISEGURO S.A. SEGUROS PERSONALES</b>, domiciliada en Av. José Ballivian No. 1059 (Calacoto), zona Sur
            de la ciudad de La Paz, teléfono 2175900, (LA COMPAÑÍA), certifica que la persona indicada en el presente
            documento se encuentra asegurada bajo la presente Póliza (ASEGURADO), contratada por la Entidad Financiera
            Banco Pyme Ecofuturo S.A. que suscribe la presente Póliza (EL TOMADOR), quien a nombre y cuenta del
            ASEGURADO contrata con LA COMPAÑÍA, la(s) cobertura(s) detalladas en las Condiciones Particulares de la
            Póliza. <br><br>

            <b>COBERTURAS Y SUMAS ASEGURADAS:</b><br>
            <b>Muerte por cualquier causa:</b> Cubre el fallecimiento del ASEGURADO durante la vigencia de la Póliza,
            por cualquier causa, sean en territorio nacional o extranjero (siempre y cuando el ASEGURADO tenga
            residencia en el Estado Plurinacional de Bolivia). LA COMPAÑÍA pagará al ASEGURADO/Beneficiario el monto de
            Bs. 5,800.- (límite establecido en las condiciones particulares de la póliza) siempre que se cumpla lo
            siguiente: (i) que la Póliza se encuentre vigente; (ii) que EL TOMADOR/ASEGURADO hubiera efectuado el pago
            de la Prima a LA COMPAÑÍA, (iii) que la causa del fallecimiento no se encuentre comprendida dentro de las
            exclusiones de Póliza, (iv) que el ASEGURADO haya tenido entre 18 años y 64 años al ingresar al seguro y no
            hubiere superado la edad máxima de permanencia de 65 años. Se contará un período de carencia de 30 días para
            contar con este beneficio si fuera fallecimiento natural. El ASEGURADO que sufra un siniestro accidental
            dentro del primer mes de su afiliación, gozará de cobertura independientemente del pago de su primera Prima.
            <br><br>

            <b>EXCLUSIONES.</b><br>
            No se cubrirán los siniestros que fueran a consecuencia de: <br>
            <b>Para la Cobertura de Muerte por cualquier Causa:</b>
            : No cubre el fallecimiento del ASEGURADO cuando el deceso se produjera como consecuencia de: a) Enfermedad
            grave y/o crónica prexistente al inicio del seguro, siempre que haya sido de conocimiento del ASEGURADO y se
            encuentre dentro del plazo de impugnación de dos años b) Suicidio, salvo que el ASEGURADO haya mantenido
            ininterrumpidamente la cobertura por al menos dos años; c) Viaje o vuelo en vehículo aéreo de cualquier
            clase, excepto como pasajero en uno operado por una empresa de transporte aéreo comercial sobre una ruta
            establecida para el transporte de pasajeros sujeto a itinerario regular; d) Participación en acto delictivo;
            e) Guerra declarada o no declarada (conflicto armado), civil o internacional; servicio militar o policial de
            cualquier índole, tanto en tiempo de paz como en tiempo de guerra; f) Actos de guerrilla, rebelión,
            sedición, motín, conmoción contra el orden público, terrorismo, huelga o tumulto popular, cuando el
            ASEGURADO hubiera participado como elemento activo; g) Participar en: inmersión submarina; montañismo; ala
            delta; paracaidismo; parapente; artes marciales, boxeo u otros deportes o disciplinas de peleas; equitación;
            carreras de caballo. Asimismo, la participación del ASEGURADO como conductor o acompañante, profesionalmente
            o como aficionado, en competencias o ensayos de velocidad o resistencia, en cualquier clase de vehículo,
            terrestre, acuático o aéreo, a motor o no; h) Participar en: actos y riesgos políticos y/o terrorismo;
            atentar contra la propia vida y la de terceros al manipular armas corto punzantes, armas de fuego; o
            realizar actividades donde se pone en grave peligro la vida e integridad física de las personas; i)
            VIH/SIDA; j) Cuando el ASEGURADO esté bajo los efectos de alcohol y/o drogas ilícitas; k) La participación
            del ASEGURADO en cualquier maniobra, experimento, exhibición, desafío o actividad notoriamente peligrosa,
            entendido por tales aquellas donde se pone en grave peligro la vida e integridad física de las personas; l)
            Epidemias declaradas como tal por las entidades gubernamentales competentes.<br><br>

            <b>REQUISITOS PARA SOLICITAR EL BENEFICIO:</b><br>
            LA COMPAÑÍA efectuará el pago que corresponda, para lo cual EL ASEGURADO deberá presentar la Carta de Aviso
            de Siniestro a EL TOMADOR tan pronto y a más tardar dentro de los tres (3) días de conocido el mismo, salvo
            fuerza mayor o impedimento justificado de acuerdo al Artículo 1028 del Código de Comercio <br>
            <b>Muerte por Cualquier Causa:</b> a) Certificado de Defunción original; b) Certificado Médico de Defunción
            o Certificado Médico o documente equivalente en original o copia legalizada (cuando corresponda); c)
            Fotocopia simple del Documento de Identidad del ASEGURADO y BENEFICIARIO; d) Formularios requeridos por la
            UIF (si aplica); e) Informe de la Autoridad Competente que certifique las circunstancias del Accidente (en
            caso que haya tomado acción en el siniestro) en copia legalizada (si aplica); f) Examen toxicológico y/o de
            alcoholemia en copia legalizada (cuando corresponda).
        </div>
        <br>

        <div style="text-align:justify; font-size:6pt;">
            <b>PROCEDIMIENTO EN CASO DE SINIESTRO: </b><br>
            LA COMPAÑÍA debe pronunciarse sobre el derecho del BENEFICIARIO dentro de los diez (10) días de recibidos
            todos los informes, evidencias, documentos y/o requerimientos adicionales acerca de los hechos y
            circunstancias del siniestro. La solicitud de LA COMPAÑÍA no podrá extenderse por mas de dos veces a partir
            de la primera solicitud de informes y evidencias debiendo LA COMPAÑÍA pronunciarse dentro del plazo
            establecido y de manera definitiva sobre el derecho de EL ASEGURADO después de la entrega por parte de EL
            ASEGURADO del último requerimiento de información en base a lo establecido en la Ley 365 de fecha 23 de
            abril de 2013, Disposiciones Adicionales Segunda, Párrafo II. LA COMPAÑÍA procederá al pago del Beneficio en
            el plazo máximo de 5 días posteriores al aviso del siniestro o tan pronto sean llenados los requerimientos
            señalados. <br><br>

            <b>VIGENCIA</b><br>
            Las Coberturas otorgadas por esta Póliza respecto de cada ASEGURADO, entrarán en vigencia a partir del día
            siguiente de la afiliación. Algunas de las coberturas tienen periodo de carencia señaladas en el presente
            documento. <br><br>

            <b>BENEFICIARIOS:</b> Los Beneficiarios son los designados por el ASEGURADO en la Solicitud de Afiliación o
            en su defecto, los herederos instituidos de acuerdo a ley. En algunos casos el ASEGURADO es el Beneficiario
            de la Póliza. <br><br>

            <b>PRIMA:</b>Las Primas tienen el objeto de garantizar la cobertura, siempre y cuando sean abonadas en el
            tiempo y forma establecidos. El no pago de la prima en la fecha de corte determinada entre LA COMPAÑÍA y el
            CONTRATANTE, no dará derecho a Cobertura aunque se haya emitido el Certificado de Cobertura correspondiente.<br><br>

            <b>TÉRMINO DEL SEGURO RESPECTO DE CADA ASEGURADO:</b> La responsabilidad de LA COMPAÑÍA respecto a cada
            ASEGURADO cesará en la primera de los siguientes casos: 1) Por solicitud escrita del ASEGURADO, mencionando
            su voluntad de rescisión, motivo por el cual la terminación del seguro se hace efecto desde la notificación
            a LA COMPAÑÍA de acuerdo al artículo 1023 del Código de Comercio; 2) El primer día calendario luego de
            transcurridos treinta (30) días en que la Prima del seguro no se encuentre pagada de acuerdo a lo
            establecido en la presente Póliza. La COMPAÑÍA rehabilitará la Póliza que hubiera concluido por falta de
            pago de Primas, en caso que EL TOMADOR pueda volver a cargar el pago de la Prima, en cuyo caso se
            considerará como fecha de rehabilitación el día en que se efectúe el cargo de Prima. En este supuesto, sólo
            se otorgará cobertura a aquellos siniestros que ocurran o se diagnostiquen a partir de la fecha de
            rehabilitación de acuerdo al artículo 1026 del Código de Comercio. 3) Al cumplir el ASEGURADO la edad máxima
            de permanencia indicada en las Condiciones Particulares. 4). Cuando se hubiera presentado documentación o
            información adulterada, fraudulenta o inexacta para acceder a alguno de los beneficios del seguro. La
            reticencia o inexactitud en las declaraciones del ASEGURADO, sobre los hechos y circunstancias que,
            conocidas por LA COMPAÑÍA, la hubieran inducido a no aceptar el contrato o a estipular condiciones
            distintas, hacen anulable el presente contrato de seguro y LA COMPAÑÍA podrá impugnar la nulidad del mismo
            durante los dos (2) primeros años de vigencia del contrato. Una vez transcurrido el plazo señalado, el
            contrato no podrá ser objeto de impugnación, salvo por el no pago de primas. 5) Cuando EL TOMADOR/ASEGURADO
            incumpla con su obligación de pago de Primas. 6) Si se comprobase que la edad real del ASEGURADO no
            estuviera comprendida dentro de los límites de admisión establecidos por LA COMPAÑÍA. Si se diera este caso,
            LA COMPAÑÍA actuará de acuerdo a lo establecido en la cláusula SEPTIMA del presente documento. 7) Si el
            siniestro se inicia dentro de la vigencia del seguro y continúa después de vencido el plazo del mismo LA
            COMPAÑÍA responde de la indemnización; pero si el siniestro se inició antes y continúa después de la
            asunción del riesgo por LA COMPAÑÍA, ésta no responde por el siniestro de acuerdo a lo establecido en el
            artículo 1026 del Código de Comercio. <br><br>

            <b>FORMA DE PAGO:</b> Anual (al contado). <br><br>

            <b>ACLARACIÓN:</b> El presente documento, resume los términos y condiciones del contrato de seguro suscrito
            por CREDISEGURO y el CONTRATANTE, cuyo texto original respalda éste documento. Los términos y condiciones de
            la Póliza pueden cambiar y que en todos los casos el CONTRATANTE informará oportunamente a los ASEGURADOS.
            <br><br>

            <b>CONDICION DE ADHESIÓN, DECLARACIONES Y AUTORIZACIONES: </b>El ASEGURADO, se adhiere voluntariamente al
            presente Seguro. <br>
            <b>Declaración:</b> El ASEGURADO declara conocer y aceptar todas las Condiciones de la Póliza.<br>
            <b>Autorización expresa:</b> : El ASEGURADO, autoriza a La Compañía su reporte a la Central de Riesgos del
            Mercado de Seguros acorde a las normativas reglamentarias de la Autoridad de Fiscalización y Control de
            Pensiones y Seguros vigente. Asimismo, El ASEGURADO autoriza expresamente a LA COMPAÑÍA a solicitar, obtener
            y dar información respecto a su información y antecedentes financieros, de seguros y de salud, a través de
            ellos o terceras personas. <br>
            EL ASEGURADO acepta la presentación, con calidad de Declaración Jurada, de la documentación de respaldo que
            solicitara LA COMPAÑIA misma que será requerida en virtud a la obligación normativa regulatoria que éste
            mantiene respecto a los controles e informes que realiza por instrucción de la Autoridad de Fiscalización y
            Control de Pensiones y Seguros, Unidad de Investigaciones Financieras u otras entidades fiscalizadoras.<br>
            Todos los beneficios a los cuales el ASEGURADO tiene derecho, se sujetan a lo estipulado en las Condiciones
            Generales, Particulares y/o Adicionales de la Póliza de SEGURO COBERTURA COMPLETA de la cual el presente
            certificado forma parte integrante. El ASEGURADO podrá requerir a LA COMPAÑÍA o a EL TOMADOR cualquier
            información adicional que considere necesaria.

        </div>
        <div style="font-size:6pt;">
            @if($format === 'pdf')
                <table style="font-size:6pt;">
                    <tr>
                        <td>
                            
                            {!! Html::image('images/logosudecs.jpg', '', []) !!}
                        </td>
                        <td style="vertical-align:middle;">
                            La presente Póliza se encuentra intermediada por Sudamericana Corredores y Asesores de
                            Seguros
                            S. A. domiciliado en la Ciudad de La Paz, Prolongación Cordero N° 137 San Jorge, teléfono
                            2433500/fax 2128329
                        </td>
                    </tr>
                </table>
            @else
                La presente Póliza se encuentra intermediada por Sudamericana Corredores y Asesores de Seguros S. A.
                domiciliado en la Ciudad de La Paz, Prolongación Cordero N° 137 San Jorge, teléfono 2433500/fax 2128329
            @endif
            <table>
                <tr>
                    <td>
                        {!! Html::image('images/firma1_ecs_nueva.jpg', '', []) !!}
                    </td>
                    <td>
                        {!! Html::image('images/firma2_ecs_nueva.jpg', '', []) !!}
                    </td>
                    <td style="width:180px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
                    <td style="vertical-align:bottom; font-size:7pt; font-weight:bold;">
                        Asegurado
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="font-size:6pt;">
                        Impreso en: {{ date('d/m/Y') }}. El presente certificado reemplaza cualquier otro certificado
                        impreso en fechas anteriores.
                    </td>
                </tr>
            </table>
        </div>
    </page>
@endif