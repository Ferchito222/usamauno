<div style="width: 775px; height: auto; border: 0px solid #0081C2; padding: 5px;">
    <div style="width: 770px; font-weight: normal; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">

        @var $font_parent = 'font-size:64%;'
        @var $font_child = 'font-size:100%;'
        @if($format === 'pdf')
            @var $font_parent = 'font-size:50%;'
            @var $font_child = 'font-size:50%;'
        @endif

        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; font-family: Arial; margin-bottom: 2px; {{$font_parent}}">
            <tr>
                <td style="width:30%; border: 0px solid #FFFF00; vertical-align: middle;" align="left" valign="top">
                    <img src="{{ asset('images/logo_ecovida_dos.jpg') }}">
                </td>
                <td style="width:40%;">

                </td>
                <td style="width:30%; vertical-align: middle;" align="right" valign="top">
                    <img src="{{ asset('images/logos_ecovida_uno.jpg') }}">
                </td>
            </tr>
            <tr>
                <td style="width: 30%;"></td>
                <td style="width: 30%;"></td>
                <td style="width: 40%; text-align: right;">N º {{ $header->issue_number }}</td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; font-family: Arial; margin-bottom: 2px; {{$font_parent}}">
            <tr>
                <td style="width:100%; font-weight:bold; text-align:center; line-height: 0.9em;">
                    CERTIFICADO DE COBERTURA INDIVIDUAL – “SEGURO ECOVIDA”<br>
                    Registrada en Autoridad de Fiscalización y Control de Pensiones y Seguros (APS) mediante<br>
                    Resolución Administrativa APS/DS/N° 1347-2015 con Código de Registro N° 209-934633-2014 09 010
                    4001<br>
                    POLIZA N° {{ $header->policy_number }}
                </td>
            </tr>
        </table>
        <div style="{{$font_parent}} font-family: Arial; margin-bottom: 2px; height: auto; text-align: left;">
            <b>Lugar y Fecha:</b> {{ $header->user->city->name }}, {{ dateToFormat($header->date_issue) }}
        </div>

        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; {{$font_parent}} font-family: Arial; padding-bottom: 3px;">
            <tr>
                <td style="width: 12%;">Nombre del Asegurado:</td>
                <td style="border-bottom: 1px solid #000; width: 25%;">
                    {{ $header->detail->client->full_name }}
                </td>
                <td style="width: 5%;">Nº de CI:</td>
                <td style="border-bottom: 1px solid #000; width: 10%;">
                    {{ $header->detail->client->dni }} {{ $header->detail->client->complement }} {{ $header->detail->client->extension }}
                </td>
                <td style="width: 11%;">Fecha de Nacimiento:</td>
                <td style="border-bottom: 1px solid #000; width: 13%;">
                    {{ dateToFormat($header->detail->client->birthdate) }}
                </td>
                <td style="width: 5%;">Teléfono:</td>
                <td style="border-bottom: 1px solid #000; width: 19%;">
                    {{ $header->detail->client->phone_number_home }}
                </td>
            </tr>
        </table>

        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 60%; height: auto; {{$font_parent}} font-family: Arial; padding-bottom: 3px;">
            <tr>
                <td colspan="3" style="border-top: 1px solid #000; border-left: 1px solid #000;
                border-right: 1px solid #000; height: 15px;">Nombro voluntariamente como Beneficiario(s) a la(s)
                    siguiente(s) persona(s):
                </td>
            </tr>
            <tr>
                <td style="width: 40%; text-align: left; border-top: 1px solid #000;
                border-left: 1px solid #000; border-right: 1px solid #000; height: 15px;">Nombre Completo
                </td>
                <td style="width: 5%; text-align: center; border-top: 1px solid #000; border-right: 1px solid #000;">%
                </td>
                <td style="width: 15%; text-align: center; border-top: 1px solid #000; border-right: 1px solid #000;">
                    Parentesco
                </td>
            </tr>
            <tr>
                <td style="width: 40%; text-align: left; border-top: 1px solid #000; border-bottom: 1px solid #000;
                border-left: 1px solid #000; border-right: 1px solid #000; height: 15px;">
                    {{ $header->detail->beneficiary->full_name }}
                </td>
                <td style="width: 5%; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000;
                border-right: 1px solid #000;">
                    {{ $header->detail->beneficiary->percentage_credit }}
                </td>
                <td style="width: 15%; text-align: center; border-top: 1px solid #000; border-bottom: 1px solid #000;
                border-right: 1px solid #000;">
                    {{ $header->detail->beneficiary->relationship }}
                </td>
            </tr>
        </table>

        <div style="width: 770px; border: 0px solid #FFFF00; text-align:justify; {{$font_parent}} margin-bottom: 3px;">
            <b>CREDISEGURO S.A. SEGUROS PERSONALES</b> domiciliada en Av. José Ballivian No. 1059 (Calacoto), zona Sur
            de la ciudad de La Paz, teléfono 2175900 (LA COMPAÑÍA), certifica que la persona indicada en el presente
            documento se encuentra asegurada bajo la presente Póliza (ASEGURADO), contratada por el Banco Pyme Ecofuturo
            S.A. (EL TOMADOR). <br>
            El presente Seguro, es un seguro opcional destinado a todas las personas naturales usuarias de la entidad
            financiera que suscribe el presente contrato, en adelante EL TOMADOR, y que hayan solicitado su afiliación
            al seguro. Este paquete de seguros incluirá las coberturas de Muerte Natural, Muerte Accidental y Gastos de
            Sepelio.
            <br>
            <b>COBERTURAS Y SUMAS ASEGURADAS:</b><br>
            <b>Muerte Natural:</b> En caso de muerte originada por causas naturales (no accidentales), la Suma Asegurada
            será de siete mil 00/100 Bolivianos (Bs. 7.000). La edad límite de afiliación es de 65 años y permanencia
            máxima de 70 años. Se contará con un período de carencia de 30 días para contar con el beneficio. El pago de
            este beneficio supone la terminación de la cobertura total de la Póliza y no da lugar a otra indemnización
            por muerte accidental. <br>
            <b>Muerte Accidental:</b> En caso de muerte originada por causas accidentales, la Suma Asegurada será de
            siete mil 00/100 Bolivianos (Bs. 7.000). La edad límite de afiliación es de 65 años y permanencia máxima de
            70 años. El pago de este beneficio supone la terminación de la cobertura total de la Póliza y no da lugar a
            otra indemnización por muerte natural o de otra cobertura adicional <br>
            <b>Gastos de Sepelio:</b> LA COMPAÑÍA pagará dos mil 00/100 Bolivianos (Bs. 2.000), a los beneficiarios del
            ASEGURADO por la muerte del mismo ya sea por causas naturales o accidentales. La edad límite de afiliación
            es de 65 años y permanencia máxima de 70 años. Se contará con un período de carencia de 30 días para la
            cobertura de Muerte Natural, no aplica período de carencia para la cobertura a causa de Muerte Accidental.
            <br>
            <b>EXCLUSIONES.</b> No se cubrirán los siniestros que fueran a consecuencia de: <br>
            <b>Para la Cobertura de Muerte Natural y Gastos de Sepelio:</b><br>
            a) Enfermedad grave y/o crónica prexistente al inicio del seguro, siempre que haya sido de conocimiento del
            ASEGURADO y se encuentre dentro del plazo de impugnación de 2 años de acuerdo al artículo 1138 del Código de
            Comercio y la normativa vigente. <br>
            b) Participación del ASEGURADO como sujeto activo en acto delictuoso o empresa criminal. <br>
            c) Guerra declarada o no declarada (conflicto armado), civil o internacional. <br>
            d) Epidemias declaradas como tal por las entidades gubernamentales competentes. <br>
            <b>Para la Cobertura de Muerte Accidental:</b> <br>
            Los incisos b) y c) mencionados en las exclusiones de la Cobertura de Muerte Natural más:
            Eventos en los que el ASEGURADO realice actividades que atenten contra su vida y/o la de terceros al
            encontrarse en estado de ebriedad o con presencia de alcohol en la sangre en proporción mayor al 0.7
            gramos/litro o se encuentre bajo los efectos de drogas ilícitas, salvo cuando el ASEGURADO hubiere sido
            sujeto pasivo en el acontecimiento que produjo el siniestro. <br>
            La participación del ASEGURADO en cualquier maniobra, experimento, exhibición, desafío o actividad
            notoriamente peligrosa, entendido por tales aquellas donde se pone en grave peligro la vida e integridad
            física de las personas. <br>
            <b>REQUISITOS PARA SOLICITAR EL BENEFICIO:</b><br>
            LA COMPAÑÍA efectuará el pago que corresponda, para lo cual EL ASEGURADO/BENEFICIARIO/TOMADOR deberá
            presentar tan pronto y a más tardar dentro de los treinta (30) días de conocido el mismo, salvo fuerza mayor
            o impedimento justificado de acuerdo al Artículo 1028 del Código de Comercio la siguiente documentación:
            <br>
            <b>Muerte Natural:</b> a) Formulario de Declaración de Siniestro proporcionado por LA COMPAÑÍA (debidamente
            llenado), b) Fotocopia simple del Documento de Identidad del ASEGURADO c) Certificado de Defunción original
            o Certificado Médico de Defunción d) Formularios requeridos por la UIF (en lo que aplica). <br>
            <b>Muerte Accidental:</b> Incisos a), b), y, d) de la cobertura de Muerte Natural más: Certificado de
            Defunción original o Certificado emitido por el Instituto de Investigaciones Forenses original o copia
            legalizada. Informe de la Autoridad Competente que certifique las circunstancias del Accidente en caso que
            haya tomado acción en el siniestro (si aplica). Examen toxicológico y/o de alcoholemia en copia legalizada
            (cuando corresponda). <br>
            <b>Sepelio:</b> Inciso a) de la cobertura de Muerte Natural más: Fotocopia simple del Documento de Identidad
            del(los) BENEFICIARIO(S), Formulario de Identificación del BENEFICIARIO.<br>
            <b>PROCEDIMIENTO EN CASO DE SINIESTRO: </b><br>
            LA COMPAÑÍA debe pronunciarse sobre el derecho del ASEGURADO/BENEFICIARIO dentro de los 30 días de recibidos
            todos los informes, evidencias, documentos y/o requerimientos adicionales acerca de los hechos y
            circunstancias del siniestro. La solicitud de LA COMPAÑÍA no podrá extenderse por mas de dos veces a partir
            de la primera solicitud de informes y evidencias debiendo LA COMPAÑÍA pronunciarse dentro del plazo
            establecido y de manera definitiva sobre el derecho del ASEGURADO después de la entrega por parte del
            ASEGURADO del último requerimiento de información en base a lo establecido en la Ley 365 de fecha 23 de
            abril de 2013, Disposiciones Adicionales Segunda, Párrafo II. LA COMPAÑÍA procederá al pago del Beneficio en
            el plazo máximo de 15 días posteriores al aviso del siniestro o tan pronto sean llenados los requerimientos
            señalados. <br>
            <b>VIGENCIA</b><br>
            Las Coberturas otorgadas por esta Póliza respecto de cada ASEGURADO, entrará en vigencia a partir del día de
            la afiliación al seguro, si el siniestro ocurre en el período en que el ASEGURADO tiene plazo para el pago
            de la prima, se descontará del pago la prima correspondiente. Algunas de las coberturas tienen periodo de
            carencia señaladas en el presente documento. <br>
            <b>BENEFICIARIOS:</b> Los Beneficiarios son los designados por el ASEGURADO en el Certificado de Cobertura o
            en su defecto, los herederos instituidos de acuerdo a ley. En algunos casos el ASEGURADO es el Beneficiario
            de la Póliza. <br>
            <b>PRIMA:</b> Anual Bs. 100 (Cien 00/100 Bolivianos). <br>
            <b>TÉRMINO DEL SEGURO RESPECTO DE CADA ASEGURADO:</b> La responsabilidad de LA COMPAÑÍA respecto a cada
            ASEGURADO cesará en la primera de los siguientes casos: 1) Al cumplir el ASEGURADO la edad máxima de
            permanencia 2) Cuando se hubiera presentado documentación o información adulterada, fraudulenta o inexacta
            para acceder a alguno de los beneficios del seguro. 3) Cuando EL TOMADOR incumpla con su obligación de pago
            de Primas El contrato caduca si no se pagan las primas en los términos convenidos y la caducidad no se
            produce de hecho sino después de transcurrido el plazo de treinta (30) días de la fecha de vencimiento para
            su pago y tal hecho no da lugar a la pérdida de los valores garantizados señalados en la póliza de acuerdo a
            lo establecido en el Artículo 1019 del Código de Comercio. 4) Si se comprobase que la edad real del
            ASEGURADO no estuviera comprendida dentro de los límites de admisión establecidos por LA COMPAÑÍA y/o 5) Si
            el siniestro se inicia dentro de la vigencia del seguro y continúa después de vencido el plazo del mismo LA
            COMPAÑÍA responde de la indemnización; pero si el siniestro se inició antes y continúa después de la
            asunción del riesgo por LA COMPAÑÍA, ésta no responde por el siniestro de acuerdo a lo establecido en el
            Artículo 1026 del Código de Comercio. <br>
            <b>FORMA DE PAGO:</b> Anual (al contado). <br>
            <b>ACLARACIÓN:</b> El presente documento, resume los términos y condiciones del contrato de seguro suscrito
            por LA COMPAÑÍA y EL TOMADOR, cuyo texto original respalda éste documento. Los términos y condiciones de la
            póliza pueden cambiar y que en todos los casos EL TOMADOR informará oportunamente a los ASEGURADOS, por
            escrito y/o por otros medios de difusión entre ellos páginas web, correo electrónico y/o medios de
            circulación nacional. <br>
            <b>CONDICION DE ADHESIÓN, DECLARACIONES Y AUTORIZACIONES: </b><br>
            El ASEGURADO, se adhiere voluntariamente al presente Seguro. <br>
            <b>Declaración:</b> El ASEGURADO declara conocer y aceptar todas las Condiciones de la Póliza y declara que
            a la fecha no tiene conocimiento de alguna enfermedad prexistente y goza de buena salud. <br>
            El ASEGURADO acepta la presentación, con calidad de Declaración Jurada, de la documentación de respaldo que
            solicitará CREDISEGURO S.A. SEGUROS PERSONALES misma que será requerida en virtud de la obligación normativa
            regulatoria que éste mantiene respecto a los controles e informes que realiza por instrucción de la Unidad
            de Investigaciones Financieras u otras entidades fiscalizadoras. <br>
            <b>Autorización expresa:</b> El ASEGURADO autoriza a La Compañía su reporte a la Central de Riesgos del
            Mercado de Seguros acorde a las normativas reglamentarias de la Autoridad de Fiscalización y Control de
            Pensiones y Seguros vigente. Asimismo, El ASEGURADO, autoriza(n) expresamente a LA COMPAÑÍA a solicitar,
            obtener y dar información respecto a sus antecedentes financieros, de seguros y de salud, a través de ellos
            o terceras personas. <br>
            Todos los beneficios a los cuales el ASEGURADO tiene derecho, se sujetan a lo estipulado en las Condiciones
            Generales, Particulares y/o Adicionales de la Póliza de Seguro de la cual el presente certificado forma
            parte integrante. El ASEGURADO podrá requerir a LA COMPAÑÍA o a EL TOMADOR cualquier información adicional
            que considere necesaria.
        </div>

        <table cellpadding="0" cellspacing="0" border="0"
               style="width: 100%; height: auto; {{$font_parent}} font-family: Arial; padding-bottom: 5px;">
            <tr>
                <td style="width: 30%;">
                    <img src="{{asset('images/logosudecs.jpg')}}">
                </td>
                <td style="width: 70%;">
                    La presente Póliza se encuentra intermediada por Sudamericana Corredores y Asesores de Seguros S. A.
                    domiciliado en la Ciudad de
                    La Paz, Prolongación Cordero N° 137 San Jorge, teléfono 2433500/fax 2128329
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table border="0" cellpadding="0" cellspacing="0" style="width:100%; {{$font_child}}">
                        <tr>
                            <td style="text-align:center; width:25%; text-align: left;">
                                <img src="{{asset('images/firma1_ecs_nueva.jpg')}}">
                            </td>
                            <td style="text-align:center; width:25%; text-align: left;">
                                <img src="{{asset('images/firma2_ecs_nueva.jpg')}}">
                            </td>
                            <td style="text-align:center; width:50%; font-weight: bold;" valign="bottom">Asegurado</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" colspan="2" style="text-align: left;">
                    “Impreso el {{ date("d/m/Y") }}. El presente certificado reemplaza cualquier otro certificado
                    impreso en fechas anteriores a la indicada.”
                </td>
            </tr>
        </table>
    </div>
</div>