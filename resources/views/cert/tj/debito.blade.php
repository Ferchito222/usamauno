<div style="width: 710px; font-weight: normal; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
    <div style="margin: 3mm 3mm 3mm 3mm;text-align: justify;font-size: 9px;">
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:70%;"><img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                <td style="width:30%; text-align: right;">
                    @if($c==1)
                        COPIA BANCO
                    @else
                        COPIA CLIENTE
                    @endif
                </td>
            </tr>
        </table>
        <div style="text-align: center">
            <strong>CERTIFICADO INDIVIDUAL DE COBERTURA</strong>
            <br />
            <strong>AL CONTRATO DE SEGURO DE PROTECCIÓN PARA TARJETAS DE CREDITO Y/O DEBITO ANUAL RENOVABLE</strong>
            <br />
            <strong>CODIGO DE REGISTRO: 113-9109327-2020 08 232 4001</strong>
        </div>
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:10%;">Póliza N°</td>
                <td style="width:15%; text-align:center;border-bottom:1px solid black;">
                    {{$header->policy_number}}
                </td>
                <td style="width:35%; text-align:right;"></td>
                <td style="width:25%;">Certificado de Cobertura N°</td>
                <td style="width:15%; text-align:center;border-bottom:1px solid black;">
                    {{$header->issue_number}}
                </td>
            </tr>
        </table>
        <br />
        <br />
        Se deja expresa constancia mediante el presente certificado, que la persona Asegurada abajo señalada ha sido admitida como integrante de la Póliza de Seguros contratada por el 
        Tomador.
        <br />
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 80%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:40%;"><strong>ASEGURADO: </strong> (Nombre y Apellidos)</td>
                <td style="width:60%; border-bottom:1px solid black;">
                    {{$detail->client->full_name}}
                </td>
            </tr>
        </table>
        <br />
        <strong>NUMERO DE TARJETA DE CRÉDITO Y/O DEBITO</strong>
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 80%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:40%;"><strong>(Sujeto a la Cobertura de Seguro): </strong></td>
                <td style="width:60%; border-bottom:1px solid black;">
                    {{$detail->card_number}}
                </td>
            </tr>
            <tr>
                <td style="width:40%;"><strong>FECHA DE INICIO DE VIGENCIA: </strong></td>
                <td style="width:60%; border-bottom:1px solid black;">
                    {{date('d/m/Y', strtotime($header->date_issue))}}
                </td>
            </tr>
            <tr>
                <td style="width:40%;"><strong>TOMADOR: </strong></td>
                <td style="width:60%; border-bottom:1px solid black;">
                    <!--{{$detail->client->full_name}}-->
                    BANCO FORTALEZA S.A. 
                </td>
            </tr>
            <tr>
                <td style="width:40%;"><strong>BENEFICIARIOS:</strong></td>
                <td style="width:60%; text-align: justify; border-bottom:1px solid black;">
                    <strong>Tarjetahabientes Titulares</strong> de las Tarjetas de Crédito y/o Debito y <strong>Beneficiario Nominado o Herederos Legales</strong>  
                    del Tarjetahabiente Asegurado en los casos que se aplique la cobertura de muerte accidental 
                    asociada al asalto. <strong>Beneficiario Nominado: {{$detail->beneficiaries->first()->full_name}} - {{$detail->beneficiaries->first()->relationship}}</strong>  
                </td>
            </tr>
        </table>
        <br />
        <strong>COBERTURAS Y CAPITALES ASEGURADOS:</strong>
        <br />
        
        <dl>
            <dt><strong>Protección contra Robo, Clonación o Extravío de la Tarjeta de Crédito y/o Debito :</strong></dt>
            <dd>
                -	<strong>Cobertura contra Robo, hurto o Extravío</strong> de la tarjeta, hasta <strong>Bs. 68.600,00</strong> como Beneficio 
                Máximo Anual. Tiene hasta <strong>180</strong>  días calendario para hacer su reclamación, a partir de la fecha de 
                la transacción fraudulenta reflejada en el estado de cuenta, donde aparezca algún cargo indebido 
                con su tarjeta robada o extraviada.
            </dd>
            <dd>
                -	<strong>Cobertura contra Clonación (Skimming)</strong> hasta <strong>Bs. 68.600,00</strong> Tiene hasta <strong>180</strong> días calendario 
                para hacer su reclamación, a partir de la fecha de la transacción fraudulenta reflejada en el estado de 
                cuenta, donde aparezca algún cargo indebido con su tarjeta clonada o falsificada.
            </dd>
            <dt><strong>Protección Personal al Asegurado:</strong></dt>
            <dd>- <strong>Cobertura contra robo como consecuencia de asalto y/o secuestro, Bs. 17.150,00 </strong> por evento, hasta <strong>2</strong> eventos al año. </dd>
            <dd>- <strong>Cobertura de traslado médico terrestre a consecuencia del asalto y/o secuestro. (sólo en Bolivia)</strong>, …… por evento, hasta  <strong>2</strong> eventos al año.</dd>
            <dd>- <strong>Cobertura de Gastos por asistencia médica a consecuencia del asalto y/o secuestro. (Sólo en Bolivia)</strong>, Hasta <strong>Bs. 10.290,00</strong></dd>
            <dd>- <strong>Cobertura de muerte accidental/Invalidez Total y/o Parcial Permanente asociada al asalto y/o secuestro, Bs. 68.600,00</strong></dd>
            <dd>- <strong>Cobertura de Gastos por recuperación de documentos (reembolso), Bs. 1.715,00</strong> por evento, hasta <strong>1</strong> eventos al año</dd>
            <dt><strong>Protección en Compras:</strong></dt>
            <dd>
                <strong>Cobertura contra Robo con violencia en la vivienda habitual o daños accidentales de los bienes adquiridos,</strong> Hasta <strong>Bs. 13.720,00</strong> por evento, hasta  
                <strong>2</strong> eventos al año, que fueron adquiridos con la tarjeta por un periodo no mayor a  <strong>60</strong> días contados desde la fecha de compra. 
            </dd>
            <dd>
                <strong>Cobertura de extensión de garantía del fabricante,</strong> Hasta <strong>Bs. 13.720,00</strong> por evento, hasta 2 eventos al año, por un periodo no mayor a <strong>12</strong> meses desde la fecha de compra. <br />
                <strong>Cobertura Adicional para transacciones realizadas con tarjeta de crédito y/o debito a través de internet</strong>, Hasta  <strong>Bs. 13.720,00</strong> por evento y/o reclamo
            </dd>
        </dl> 
        <br />
        <strong>CLAUSULAS ADICIONALES:</strong>
        <br /><br />
        <strong>PRIMA TOTAL MENSUAL:</strong>
        <br />
        Las Primas serán cargadas cada mes vencido a la (s) cuenta (s) asociada (s) de la tarjeta de crédito y/o debito registrada<br />
        Prima Total Mensual a facturar por el Asegurador: {{$header->currency}}. {{$detail->premium}}  (o su equivalente en dólares T.C. 6.86) (por tarjeta)
        <br /><br />
        <strong>CONTRATO PRINCIPAL (PÓLIZA MATRIZ)</strong>
        <br />
        “FORTALEZA DE SEGUROS Y REASEGUROS S.A.”, Compañía de Seguros y Reaseguros asegura al TARJETAHABIENTE, según las condiciones descritas 
        en el Contrato del Seguro de Protección de Tarjetas de Crédito y/o Debito ; celebrado entre “FORTALEZA DE 
        SEGUROS Y REASEGUROS S.A.” y el Tomador <strong>BANCO FORTALEZA S.A.</strong>, condiciones que forman parte integrante del seguro aquí concedido.
        <br />
        El presente Certificado de Seguro tendrá valor siempre y cuando la póliza esté vigente y sus primas se encuentren 
        pagadas de acuerdo a las Condiciones de la póliza.
        <br /><br />
        <strong>ADHESIÓN VOLUNTARIA DEL ASEGURADO</strong>
        <br />
        El Asegurado al momento de autorizar el cargo mensual de la prima a su (s) cuenta (s) asociada a la tarjeta con el 
        Tomador declara su consentimiento VOLUNTARIO para ser asegurado en la póliza arriba indicada y declara conocer y 
        estar de acuerdo con las condiciones del contrato de seguros.
        <br />
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:25%;"></td>
                <td style="width:15%; text-align:center;"></td>
                <td style="width:60%; text-align:center;" colspan="3">
                    <strong>“FORTALEZA DE SEGUROS Y REASEGUROS S.A.”</strong>
                </td>
            </tr>
            <tr>
                <td style="width:25%;"></td>
                <td style="width:15%; text-align:center;"></td>
                <td style="width:25%; text-align:center;">
                    <img src="{{ base_path('public/images/tj/firma_autorizada_1.jpg') }}" height="90px">
                </td>
                <td style="width:10%; text-align:center;"></td>
                <td style="width:25%; text-align:center;">
                    <img src="{{ base_path('public/images/tj/firma_autorizada_2.jpg') }}" height="90px">
                </td>
            </tr>
            <tr>
                <td style="width:25%;">FIRMA DEL ASEGURADO</td>
                <td style="width:15%; text-align:center;"></td>
                <td style="width:25%; text-align:center;">
                    FIRMA AUTORIZADA
                </td>
                <td style="width:10%; text-align:center;"></td>
                <td style="width:25%; text-align:center;">
                    FIRMA AUTORIZADA
                </td>
            </tr>
        </table>
        <br />
        <br />
        <div style="text-align: center;">
            EN CASO DE SINIESTRO SÍRVASE CONTACTARSE DIRECTAMENTE CON : SUDAMERICANA CORREDORES Y ASESORES DE SEGUROS LINEA GRATUITA (+591) 800-10-3070 
        </div>
        <br />
        <br />
        <div style="text-align: center; font-weight: bold;">
            IMPORTANTE:<br />
            Una vez cancelada la prima el Asegurado podrá descargar su factura en nuestra página web http://181.188.151.74:8070/factura/login
        </div>
    </div>
    
    <page><div style="page-break-before: always;">&nbsp;</div></page>
    
    <div style="margin: 3mm 3mm 3mm 3mm;text-align: justify; font-size: 5.5px;">
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 7px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:70%;"><img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                <td style="width:30%;"></td>
            </tr>
        </table>
        <div style="text-align: center">
            La adhesión a esta póliza es de carácter voluntario y puede ser reemplazada por otra de similares características<br />
            <strong>
            REGLAS DEL CONTRATO DE SEGURO DE PROTECCIÓN DE TARJETAS DE CRÉDITO Y/O DEBITO ANUAL RENOVABLE
            </strong>
        </div>
        <br />
        <strong>SECCIÓN 1: COBERTURA DE PROTECCIÓN CONTRA ROBO, CLONACIÓN O EXTRAVÍO DE LA TARJETA</strong>
        <br />
        <ul type="1">
            <li>
                <strong>COBERTURA CONTRA ROBO, HURTO O EXTRAVÍO</strong>
                <br />
                La Compañía indemnizará al Asegurado por las pérdidas económicas que pudiera sufrir como consecuencia de Robo, 
                Asalto o Clonación de la tarjeta de crédito y/o débito, de acuerdo a lo que se define más abajo:
                <br />
                Robo, hurto o extravío significa la apropiación indebida de dinero que es retirado fraudulentamente 
                de un Cajero Automático ATM o, el robo de la tarjeta de crédito y/o débito asegurada contra la voluntad 
                o por inadvertencia de su poseedor y posterior retiro ilegal o fraudulento mediante dicha tarjeta de los fondos de la 
                cuenta del cliente o, el robo de la tarjeta de crédito y/o débito asegurada contra la voluntad o por inadvertencia 
                de su poseedor que posteriormente es utilizada por la persona o personas que se apropiaron indebidamente de la 
                tarjeta de crédito y/o débito del Asegurado efectuando de esta forma consumos fraudulentos, incluyendo el fraude, 
                sin que para dichos fines haya existido amenaza, secuestro, extorsión y/o violencia en contra de la persona del Asegurado.
                <br />
                Sin perjuicio del cumplimiento de las obligaciones establecidas al Asegurado como condición precedente a la responsabilidad 
                del Asegurador, las pérdidas que sufra el Asegurado en su cuenta por los eventos de Robo hurto o extravío y/o fraude tal 
                como han sido definidos quedarán cubiertos por esta póliza dentro y/o fuera del territorio boliviano.
            </li>
            <li>
                <strong>COBERTURA CONTRA CLONACIÓN “SKIMMING”</strong>
                <br />
                Clonación significa la reproducción ilegal de la tarjeta de crédito y/o débito del Asegurado o de 
                los códigos electrónicos de la misma por parte de personas maliciosas para realizar de esta forma, 
                ya sean consumos y/o retiros de fondos de un cajero automático (ATM) mediante la utilización de dicha 
                tarjeta y/o de los códigos electrónicos falsificados, ya sea mediante el uso del pin o no, afectando de 
                esta manera, la cuenta del Asegurado.  Sin que para dichos fines haya existido amenaza, secuestro, extorsión 
                o violencia en contra de la persona del Asegurado.
                <br />
                Queda entendido y convenido que, la confirmación del evento de Clonación “Skimming”, en todos los 
                casos estará sometida al dictamen emitido por la Administradora de Tarjetas de Crédito y/o débito del Tomador
                , constituyéndose este dictamen como la confirmación del evento de Clonación “Skimming”.  
                <br />
                Sin perjuicio del cumplimiento de las obligaciones establecidas al Asegurado como condición precedente a la 
                responsabilidad del Asegurador, las pérdidas que sufra el Asegurado en su cuenta por eventos de Clonación “Skimming”, 
                tal como han sido definidos quedarán cubiertas por esta póliza dentro o fuera del territorio boliviano.
            </li>
        </ul>
        <br />
        <strong>SECCIÓN 2: COBERTURA DE PROTECCIÓN PERSONAL AL ASEGURADO</strong>
        <br />
        <strong>QUEDA ENTENDIDO Y CONVENIDO QUE, LAS DEFINICIONES Y EXTENSIONES DE COBERTURA DETALLADAS EN ESTA SECCION, 
            ESTÁN DIRECTAMENTE VINCULADAS CON EVENTOS CUBIERTOS BAJO LA COBERTURA DE ROBO COMO CONSECUENCIA DE ASALTO 
            Y/O SECUESTRO TAL COMO HA SIDO DEFINIDA Y, POR LO TANTO, SE CONSTITUYEN EN COBERTURAS COMPLEMENTARIAS PARA 
            TODOS LOS EFECTOS DE ESTA PÓLIZA:</strong>
        <br />
        <ul type="1">
            <li>
                <strong>COBERTURA CONTRA ROBO COMO CONSECUENCIA DE ASALTO Y/O SECUESTRO</strong>
                <br />
                Robo como consecuencia de Asalto y/o secuestro, significa cualquier pérdida ocasionada en la cuenta del Asegurado sea por 
                consumos fraudulentos y/o retiro de fondos de un cajero automático (ATM) como consecuencia de asalto y/o 
                secuestro perpetrado al Asegurado y en tales circunstancias sean consumadas las pérdidas en la cuenta del Asegurado. 
                Esta cobertura, incluye sin limitar el alcance de esta cobertura, la divulgación obligada mediante amenaza o 
                extorsión de las claves y/o pines de la cuenta del Asegurado, para consumar los retiros ilegales y ocasionar 
                las pérdidas en la cuenta del Asegurado.  
                <br />
                Asalto y/o Secuestro significan sin limitar el objeto de contratación de esta cobertura, actos de apoderamiento 
                intencional e ilegal cometido por una persona o grupo de personas en contra del Asegurado con o sin 
                secuestro del mismo. Para fines de este seguro Secuestro significa, la retención ilegal del Asegurado por parte 
                de personas maliciosas. Siempre que los actos mencionados hayan ocasionado pérdida en la cuenta del Asegurado.
                <br />
                Los actos mencionados, deben involucrar ya sea el uso de armas peligrosas y/o amenazas de infligir lesión 
                corporal al Asegurado y/o la provocación de lesiones corporales al Asegurado incluyendo la muerte, sea que 
                las pérdidas hayan sido producidas con o sin la retención ilegal del Asegurado por parte de los delincuentes. 
                <br />
                Esta cobertura se extiende a amparar las pérdidas en la cuenta del Asegurado por medio de hechos violentos como 
                robo con fractura o robo por escalamiento del domicilio o local en donde se encuentre el Asegurado y como 
                consecuencia de dichos actos, la tarjeta de débito haya sido objeto de robo y posterior utilización de la misma 
                para efectuar consumos y/o retiros fraudulentos de la cuenta del Asegurado bajo cualquier modalidad ilegal que sea 
                utilizada por los delincuentes. 
                <br />
                Las pérdidas que sufra el Asegurado en su cuenta por eventos de Robo como consecuencia de Asalto y/o secuestro, 
                tal como han sido definidos quedarán cubiertas por esta póliza dentro o fuera del territorio boliviano.
                <br />
                Los códigos penales y civiles de la jurisdicción en donde se cometen dichos actos constituyen las bases 
                de interpretación para el Asegurador de los términos mencionados. El Atestado y/o Manifiesto Policial emitido en 
                el lugar de los hechos, servirá como base para interpretar si el evento es amparable, junto con las 
                certificaciones de constancia de pérdida en la cuenta del Asegurado emitidas por el Tomador.
                <br />
                Queda entendido y convenido que, las definiciones y extensiones de coberturas detalladas a continuación, 
                están directamente vinculadas con eventos cubiertos bajo la cobertura de robo como consecuencia de asalto y/o 
                secuestro tal como ha sido definida y, por lo tanto, se constituyen en coberturas complementarias para todos 
                los efectos de esta póliza.
            </li>
            <li>
                <strong>COBERTURA DE TRASLADO MEDICO TERRESTRE A CONSECUENCIA DE ASALTO Y/O SECUESTRO</strong>
                <br />
                Traslado Médico terrestre a consecuencia del asalto y/o secuestro significa, el gasto que se acredite 
                haber erogado dentro de Territorio Boliviano por, la contratación necesaria de una ambulancia o cualquier 
                otro medio de trasporte terrestre con acompañamiento de personal especializado en primeros auxilios, con el 
                fin de trasladar al Asegurado herido a un centro médico asistencial a causa de las lesiones infligidas por 
                el Asalto y/o Secuestro cubierto por esta póliza. 
                <br />
                Esta cobertura y servicio funcionará mediante reembolso de los gastos facturados única y exclusivamente 
                en Territorio Boliviano y presentados al Asegurador, hasta el límite estipulado en las Condiciones Particulares de la póliza. 
                <br />
                Queda entendido y convenido que esta extensión no ampara en ningún caso:
                <dl>
                    <dd>a) Gastos de traslado médico fuera de Territorio Boliviano.</dd>
                    <dd>b) Gastos de traslado mediante ambulancia aérea o cualquier otro medio que no sea terrestre. </dd>
                    <dd>c) Traslados médicos por dolencias que no se atribuyan a lesiones infligidas al Asegurado por parte de los delincuentes.</dd>
                    <dd>d) Gastos que excedan el límite de responsabilidad del Asegurador, establecido en las Condiciones Particulares de esta póliza para esta cobertura.</dd>
                </dl>
            </li>
            <li>
                <strong>COBERTURA DE GASTOS POR ASISTENCIA MÉDICA A CONSECUENCIA DE ASALTO Y/O SECUESTRO</strong>
                <br />
                Gastos por asistencia médica a consecuencia de Asalto y/o secuestro significan, los gastos 
                médicos erogados dentro de Territorio Boliviano y facturados por el centro médico asistencial y por 
                los profesionales médicos que dieron asistencia al Asegurado herido, por las lesiones sufridas 
                como consecuencia del Asalto y/o Secuestro cubierto por esta póliza. 
                <br />
                La cobertura garantiza hasta el límite máximo de responsabilidad del Asegurador establecido en 
                las Condiciones Particulares de esta póliza, el reembolso de los gastos que estén directamente 
                vinculados a los servicios de atención médica brindados al Asegurado por las lesiones infligidas 
                en el Asalto y/o secuestro, sobre la base de los Aranceles Médicos Bolivianos. 
                <br />
                Queda entendido y convenido que esta extensión no ampara en ningún caso:
                <br />
                <dl>
                    <dd>a)	Gastos por la asistencia recibida por el Asegurado fuera de Territorio Boliviano.</dd>
                    <dd>b)	Gastos médicos por dolencias que no se atribuyan a lesiones infligidas al Asegurado por parte de los delincuentes. </dd>
                    <dd>c)	Gastos médicos que excedan el límite de responsabilidad del Asegurador, establecido en las Condiciones Particulares de esta póliza para esta cobertura.</dd>
                </dl>
            </li>
            <li>
                <strong>COBERTURA DE MUERTE/invalidez total y/o parcial permanente ACCIDENTAL asociadA al asalto Y/O SECUESTRO</strong>
                <br />
                Cobertura de MUERTE/INVALIDEZ TOTAL Y/O PARCIAL PERMANENTE ACCIDENTAL  Asociada al Asalto y/o secuestro significa, 
                el beneficio que el Asegurador garantiza indemnizar hasta el límite de responsabilidad establecido en las Condiciones 
                Particulares a favor de los herederos legales, en caso de muerte/invalidez total y/o parcial permanente del Asegurado 
                cuya causa esté debidamente certificada por las Autoridades Competentes y por los Profesionales Médicos como causa 
                asociada y/o vinculada a las lesiones infligidas por los delincuentes en el Asalto y/o Secuestro cubierto por esta 
                Póliza, es decir, sin cuyo acontecimiento el deceso del Asegurado no se hubiere producido.
                <br />
                Esta cobertura funcionará ya sea que el Asalto y/o Secuestro cubierto por la póliza haya sido perpetrado al Asegurado 
                dentro o fuera del territorio boliviano.
                <br />
                El límite de responsabilidad del Asegurador para esta cobertura, funcionará en forma independiente y no complementaria 
                a los otros beneficios y gastos cubiertos por esta póliza vinculados a Asalto y/o Secuestro.
                <br />
                Queda entendido y convenido que esta extensión no ampara en ningún caso:
                <br />
                <dl>
                    <dd>a)	Cualquier episodio de asalto y/o secuestro experimentado por el Asegurado en el cual no se hayan 
                        producido lesiones corporales por parte de los delincuentes.</dd>
                    <dd>b)	Cualquier episodio de asalto y/o secuestro experimentado por el Asegurado que no esté cubierto bajo la 
                        cobertura contra Robo como Consecuencia de Asalto y/o Secuestro, tal como ha sido definida.</dd>
                    <dd>c)	Muerte natural del Asegurado/Invalidez total y/o parcial permanente que no esté asociada y/o 
                        vinculada a las lesiones infligidas por los delincuentes en el Asalto y/o Secuestro cubierto por esta póliza.</dd>
                </dl>
            </li>
            <li>
                <strong>COBERTURA DE GASTOS POR RECUPERACIÓN DE DOCUMENTOS</strong>
                <br />
                Cobertura de Gastos por Recuperación de Documentos significan, los gastos que el Asegurado acredite 
                haber realizado en Territorio Boliviano, con el propósito de obtener la reposición y/o duplicados de 
                documentos personales y otras tarjetas de crédito o débito que hayan sido objeto de robo o sustracción en 
                el asalto y/o secuestro cubierto por la póliza ya sea que el evento de Asalto y/o Secuestro haya ocurrido 
                dentro o fuera de territorio boliviano.                      
                <br />
                El beneficio funcionará mediante reembolso y hasta el límite de responsabilidad del Asegurador establecido en 
                las Condiciones Particulares de esta póliza para esta cobertura, mediante presentación de documentos que sustenten los 
                cobros realizados al Asegurado por los entes emisores respectivos y demás comprobantes de gastos relacionados 
                directamente con la reposición de los documentos robados y/o perdidos en el Asalto y/o Secuestro según se 
                especifiquen en el correspondiente Atestado o Manifiesto Policial emitido por las Autoridades Competentes.
            </li>
        </ul>
        <br />
        <strong>Cobertura Adicional:</strong>
        <br />
        <strong>SECCIÓN 3: COBERTURA DE PROTECCIÓN DE COMPRAS</strong>
        <br />
        <ul type="1">
            <li>
                PROTECCIÓN DE COMPRAS ROBO CON VIOLENCIA O DAÑOS ACCIDENTALES<br />
                Cubre el robo con violencia en la vivienda habitual del asegurado o los daños accidentales de los 
                bienes adquiridos con la tarjeta de crédito y/o débito por un periodo no mayor a 60 días contados desde la fecha de compra.
            </li>
            <li>
                EXTENSIÓN DE GARANTÍA DEL FABRICANTE<br />
                Duplica la garantía establecida por el fabricante de los bienes adquiridos con la tarjeta de 
                crédito y/o débito hasta un periodo no mayor de 12 meses desde la fecha de compra.
            </li>
            <li>
                TRANSACCIONES REALIZADAS A TRAVÉS DE INTERNET<br />
                Se extiende a cubrir las pérdidas económicas que el asegurado pudiera sufrir a consecuencia 
                de compras y/o consumos fraudulentos a través de internet, realizados con la tarjeta de crédito 
                y/o débito asegurada, que el asegurado acredite no haber realizado, hasta el límite establecido 
                en las condiciones particulares de la póliza.
                <br />
                El asegurado deberá cumplir con el proceso de habilitación de la tarjeta de crédito y/o débito 
                asegurada, de acuerdo a políticas y a través de los mecanismos establecidos por la Entidad Financiera.
            </li>
        </ul>
        <br />
        <strong><u>CONDICIONES DE ADHESIÓN DE LOS ASEGURADOS</u></strong>
        <br />
        <i>Podrán pertenecer al colectivo asegurado todos los Tarjetahabientes Titulares y Adicionales que reúnan los 
        requisitos o condiciones de adhesión en la fecha de efecto o con posterioridad y figuren en la última planilla 
        de declaración de asegurados elaborada para tal efecto por el “Tomador” y que forma parte de la póliza.</i>
        <br />
        <i>La cobertura para cada asegurado será aplicable de la siguiente forma:</i>
        <br />
        <i>- Nuevos tarjeta-habientes: desde la fecha correspondiente a operaciones otorgadas por el tomador antes del 
         inicio de la vigencia de la póliza; quedan aseguradas sin ningún tipo de limitación, desde el inicio de la 
         vigencia de la presente póliza y/o sus renovaciones.</i>
        <br />
        <i>En cada vencimiento de la póliza, este seguro será renovado automáticamente por un nuevo periodo anual. 
             En este periodo el asegurado se sujetara a los términos y condiciones de la vigencia renovada de la 
             póliza por una nueva anualidad.</i>
        <br />
        <strong><u>EXCLUSIONES GENERALES: </u></strong>
        <br />
        <i>La presente Póliza no cubre ninguna pérdida, daño o costo resultante de, o que surja de:</i>
        <br />
        <ul type="1">
            <li><i>Que el Asegurado, su cónyuge o cualquier pariente del Asegurado, dentro del cuarto grado de consanguinidad o segundo de afinidad sea autor o cómplice del Robo, Asalto, Secuestro o Clonación.</i></li>
            <li><i>Que el Robo, Asalto o Atraco sea cometido durante cualquiera de las siguientes situaciones:</i></li>
            <li><i>Cualquier pérdida consecuencial, incluyendo pero no limitándose a: Interrupción de Negocios, Demora, Pérdida de Mercado, Lucro Cesante, Retraso o cualquier otra pérdida similar.
                <br />
                Guerra internacional o civil, actos perpetrados por fuerzas extranjeras, hostilidades u operaciones bélicas (sea que se haya declarado la guerra o no), rebelión, sedición o usurpación de poder.
                </i></li>
            <li><i>La pérdida de dinero efectivo ocurrida a un usuario no autorizado de la tarjeta asegurada.</i></li>
            <li><i>La perdida de cualquier otro instrumento de comercio negociable, incluyendo cheques y cheques viajeros; como tampoco la perdida de cualquier otra cantidad de dinero que estuviere en posesión del asegurado al momento de ocurrir el delito y que no sea la registrada como retirada del cajero automático por el asegurado.</i></li>
            <li><i>Las pérdidas resultantes del uso de una tarjeta bancaria con la intención de defraudar al titular, por parte de una persona autorizada por éste.</i></li>
            <li><i>Transacciones realizadas a través de ventas por catálogo, por teléfono o por cualquier medio de transmisión de datos en que no exista la firma manuscrita del titular o adicional.</i></li>
            <li><i>Incumplimiento de sus obligaciones por parte del asegurado o administrador o usuario.</i></li>
            <li><i>Uso fraudulento de una tarjeta bancaria por parte del usuario titular o adicionales de la respectiva tarjeta.</i></li>
        </ul>
        <br />
        <strong><u>PÉRDIDA DE LA CONDICIÓN DE PERTENENCIA AL GRUPO</u></strong>
        <br />
        <i>Los beneficios otorgados por este seguro cesaran automáticamente sin derecho a devolución de primas en los siguientes casos:</i>
        <br />
        <dl>
            <dd>-	Por cancelación de la tarjeta de crédito y/o debito</dd>
            <dd>-	Por la expiración sin renovación de la tarjeta de crédito y/o debito</dd>
            <dd>-	Por el incumplimiento o mora en el pago de las primas según reglamento interno del Tomador</dd>
            <dd>-	En la fecha de vencimiento de las pólizas, salvo en los siguientes casos:</dd>
            <dd>a)	Los casos en los que las eventualidades previstas y cubiertas por esta póliza hayan ocurrido dentro de la vigencia de la póliza y el tomador haya cumplido con la condición “plazo de aviso” inserta en las condiciones particulares.</dd>
            <dd>b)	La póliza haya sido renovada por el tomador a su vencimiento (sin perjuicio de que las condiciones puedan modificarse en cada renovación).</dd>
        </dl>
        <br />
        <strong>Informes y Evidencias en caso de siniestro</strong>
        <br />
        El Asegurado o beneficiario y el Tomador según el caso, facilitarán como requerimiento del Asegurador en 
        caso de siniestro, los siguientes informes y evidencias conducentes a la determinación de la causa, 
        identidad de las personas o  intereses asegurados y cuantía de pérdidas, sin perjuicio de la obligación 
        del Tomador y Asegurado de permitir al Asegurador realizar las indagaciones pertinentes y necesarias que 
        cada caso amerite para la determinación de procedencia o improcedencia de los reclamos presentados
    </div>
    <page><div style="page-break-before: always;">&nbsp;</div></page>
    
    <div style="margin: 3mm 3mm 3mm 3mm;text-align: justify; font-size: 5.5px;">
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 7px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:70%;"><img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                <td style="width:30%;"></td>
            </tr>
        </table>
        <strong><u>Casos de Robo, hurto o Extravío de la Tarjeta de Débito y/o Crédito:</u></strong>
        <br />
        <ul type="1">
            <li>Carta de reclamo al Asegurador emitida por el Tomador con confirmación y cuantificación de pérdidas en la cuenta del Asegurado. </li>
            <li>Copia de la carta del Asegurado al Tomador por pérdidas detectadas e identificadas en su Estado de Cuentas 
                y/o por denuncia de robo y/o extravío de la Tarjeta de Débito y/o Crédito siempre que estos hechos le hayan 
                originado pérdida en su cuenta y/o en defecto de la carta de reclamo a la Entidad Financiera copia del 
                “Formulario de Reclamo del Tarjetahabiente” debidamente llenado en formato que para tal efecto es proporcionado 
                por la Entidad Financiera.</li>
            <li>Copia de la denuncia hecha a la policía por el Tajetahabiente afectado, con el relato de las circunstancias del 
                robo y/o hurto y/o extravío de la Tarjeta de Crédito y/o débito según sea el caso, carta del asegurado con 
                el relato detallado de los hechos que dieron origen a sus pérdidas.</li>
            <li>Copia del Estado de Cuentas en el que aparezcan las transacciones fraudulentas.</li>
            <li>Copia del carnet de identidad del tarjetahabiente.</li>
            <li>Copia del pasaporte del tarjetahabiente en caso de que el robo y/o hurto y/o extravío se hubieren producido fuera del país.  </li>
            <li>Informe sobre las transacciones reclamadas emitida por la Administradora de Tarjetas de Crédito y/o débito.</li>
            <li>Copia del Contrato de Tarjetas de Crédito y/o débito suscrito entre el Tarjetahabiente y la Entidad Financiera.</li>
        </ul>
        <br />
        <strong><u>Casos de Clonación:</u></strong>
        <br />
        <ul type="1">
            <li>Carta de reclamo al Asegurador emitida por el Tomador con confirmación y cuantificación de pérdidas en la cuenta del Asegurado. </li>
            <li>
                Copia de la carta del Asegurado al Tomador por pérdidas detectadas e identificadas en su Estado de Cuentas y/o por denuncia de transacciones fraudulentas efectuadas con su
                <br />
                Tarjeta de Crédito y/o débito en defecto de la carta de reclamo a la Entidad Financiera copia del “Formulario de Reclamo del Tarjetahabiente” debidamente llenado en formato que para tal efecto es proporcionado por la Entidad Financiera.
            </li>
            <li>Copia del Estado de Cuentas en el que aparezcan las transacciones fraudulentas.</li>
            <li>Copia del carnet de identidad del tarjetahabiente.</li>
            <li>Copia del pasaporte del tarjetahabiente en caso de que las transacciones fraudulentas hayan sido efectuadas fuera del país.  </li>
            <li>Copia de la tarjeta de débito afectada (anverso y reverso).</li>
            <li>Informe y dictamen sobre clonación y/o Skimming emitido por la Administradora de Tarjetas de Crédito y/o débito y copia de los vouchers evaluados.</li>
            <li>Copia del Contrato de la tarjeta de Crédito y/o débito suscrito entre el tarjetahabiente y la entidad financiera</li>
        </ul>
        <br />
        <strong><u>Casos de Robo por Asalto y/o Secuestro del Asegurado:</u></strong>
        <br />
        <ul type="1">
            <li>Carta de reclamo al Asegurador emitida por el Tomador con confirmación y cuantificación de pérdidas en la cuenta del Asegurado. </li>
            <li>Copia de la carta del Asegurado o de sus familiares o representantes legales (según sea el caso) al Tomador con el relato de los hechos y reclamo de pérdidas en la cuenta del Asegurado como consecuencia del robo por Asalto y/o Secuestro cometidos al Asegurado. </li>
            <li>Copia de la denuncia presentada a las Autoridades Policiales del lugar en contra de los que resultaren autores de los delitos de Robo por Asalto y/o Secuestro perpetrado al Asegurado.</li>
            <li>Copia del Informe Policial y/o Manifiesto y/o Atestado Policial, emitido por las autoridades como resultado de las investigaciones. </li>
            <li>Copia del Estado de Cuentas en el que aparezcan las transacciones fraudulentas.</li>
            <li>Copia del carnet de identidad del tarjetahabiente.</li>
            <li>Copia del pasaporte del tarjetahabiente en caso de que el evento haya ocurrido fuera del país.</li>
            <li>Informe emitido por la Administradora de Tarjetas de Crédito y/o débito sobre las transacciones reclamadas.</li>
            <li>Copia del Contrato de Débito suscrito entre el tarjetahabiente y la Entidad financiera</li>
        </ul>
        <br />
        <strong><u>En caso de aplicación de las coberturas adicionales asociadas al Asalto y/o Secuestro, la Aseguradora requerirá adicionalmente:</u></strong>
        <br />
        <ul type="1">
            <li>Original del Certificado Médico sobre lesiones del Asegurado.</li>
            <li>Copia de las facturas y/o comprobantes que acrediten los gastos incurridos por concepto de 
                traslado médico terrestre, gastos por asistencia médica al Asegurado, gastos por recuperación de documentos.</li>
            <li>En caso de Muerte del Asegurado: Informe del Médico Forense, Original del Certificado 
                de Defunción, Original de la Declaratoria de Herederos, Historia Clínica del Asegurado 
                (siempre que sea aplicable al caso). Si el fallecimiento ocurriese fuera del país, el Asegurador 
                requerirá que los documentos de acreditación de muerte del Asegurado cuenten con las legalizaciones 
                correspondientes en Bolivia.   </li>
        </ul>
        <br />
        <strong><u>Caso de Protección de Compra Robo Con Violencia o Daños Accidentales</u></strong>
        <br />
        <ul type="a">
            <li>Carta de reclamo al Asegurador emitida por el Tomador con la confirmación y cuantificación del robo o daño sufrido por el Asegurado. </li>
            <li>Copia de la carta del Asegurado al Tomador denunciando el robo o daño de los bienes adquiridos con su tarjeta de crédito y/o débito.</li>
            <li>En el caso de Robo, copia de la denuncia hecha a la policía por el Tarjetahabiente afectado, con el relato de las circunstancias del robo y el detalle de los bienes robados adquiridos con su tarjeta.</li>
            <li>Copia del carnet de identidad del tarjetahabiente.</li>
            <li>Copia del voucher y Factura de compra de los bienes adquiridos.</li>
        </ul>
        <br />
        <strong><u>Caso de Extensión de Garantía del Fabricante</u></strong>
        <br />
        <ul type="1">
            <li>Carta de reclamo al Asegurador emitida por el Tomador solicitando se aplique la cobertura de extensión de garantía del fabricante. </li>
            <li>Copia de la carta del Asegurado al Tomador solicitando se extienda la Garantía del Fabricante del bien adquirido con su tarjeta de crédito y/o débito.</li>
            <li>Copia del carnet de identidad del tarjetahabiente.</li>
            <li>Copia del voucher y Factura de compra del bien adquirido.</li>
            <li>Copia de la garantía del Fabricante.</li>
        </ul>
        <br />
        <strong><u>Caso de transacciones realizadas con tarjeta de crédito/debito a través de internet</u></strong>
        <br />
        <ul type="1">
            <li>Carta de reclamo al Asegurador emitida por el Tomador con confirmación y cuantificación de pérdidas en la cuenta del Asegurado.</li>
            <li>Copia del reclamo presentado a la Entidad Financiera mediante el sistema SARC (Sistema de Atención de reclamos de Clientes) por pérdidas detectadas e identificadas en su Estado de Cuentas y/o por denuncias de compras por internet fraudulentas efectuadas en su Tarjeta de crédito y/o débito.</li>
            <li>Copia del Estado de Cuentas en el que aparezcan las compras por internet fraudulentas efectuadas en su Tarjeta de crédito y/o débito.</li>
            <li>Fotocopia del carnet de identidad del Tarjeta-habiente</li>
            <li>Fotocopia de la Tarjeta de crédito y/o debito afectada (anverso y reverso).</li>
            <li>Informe emitido por la Administradora de Tarjetas, copia de los Vouchers evaluados, incluyendo grabación de la llamada de habilitación de compras por internet. </li>
            <li>Copia del Contrato de la tarjeta de crédito y/o debito suscrito entre el Tarjeta-habiente y la Entidad Financiera.</li>
            <li>Copia del Formulario de Habilitación de Compras por internet (si es que hubiere)</li>
            <li>Detalle de las compras por internet de los últimos 6 meses del Tarjeta-habiente</li>
        </ul>
        <br />
        <strong><u>Aviso de Siniestro</u></strong>
        <br />
        <strong>(En Concordancia con los Artículo 1028 y 1030 del Código de Comercio)</strong>
        <br />
	Queda entendido y convenido que la obligación de aviso de siniestro al asegurador en los plazos 
        estipulados en el artículo 1028 y 1030 del código de comercio; serán aplicables al tomador y no 
        al asegurado o beneficiario y podrán ser efectuados por cualquier vía, el tomador tendrá 45 días para 
        dar aviso del siniestro a la compañía Aseguradora.
	<br />
        Para los siniestros de clonación, el tomador tendrá un plazo de aviso al asegurador de 30 días calendarios contados 
        a partir de la fecha de recepción del dictamen emitido por la administradora de tarjetas de crédito.
        <br />
        <strong><u>Prima ganada</u></strong>
        <br />
        En caso que la Compañía indemnizara un siniestro por un monto superior al 85% de la prima anual de 
        la Póliza, ésta será considerada como ganada por la Compañía. Consecuentemente, la anulación de la 
        póliza por parte del asegurador no admitirá devolución de prima.
        <br />
        Los seguros con duración menor a un año que resulten afectados por siniestros cuyo monto exceda el de 
        la prima pactada, devengarán en forma automática la totalidad de la prima pactada.
        <br />
        En cualquiera de los casos, de existir un saldo de la prima total pactada, vencida o por vencer, será 
        deducida de todo siniestro indemnizable.
        <br />
        <strong><u>Plazo para pronunciarse</u></strong>
        <br />
        La Compañía debe pronunciarse sobre el derecho del Asegurado o Beneficiario dentro de los treinta días (30) 
        calendario de recibida la información y evidencias requeridas.
        <br />
        El plazo de treinta (30) días calendario mencionado, fenece con la aceptación o rechazo del siniestro.
        <br />
        Éste plazo se interrumpe con la solicitud de la Compañía al Asegurado de que se complementen los 
        requerimientos contemplados en los Artículos correspondientes en cada Sección del presente Contrato y no 
        vuelve a correr hasta que el Asegurado haya cumplido con tales requerimientos.
        <br />
        La solicitud de complementos por parte de la Compañía, no podrá extenderse por más de dos veces a 
        partir de la primera solicitud de informes y evidencias, debiendo pronunciarse dentro el plazo establecido 
        de manera definitiva sobre el derecho del asegurado, después de la entrega por parte del asegurado del ultimo requerimiento de información.
        <br />
        El silencio de la Compañía, vencido el termino para pronunciarse o vencidas las solicitudes de complementación, 
        importa la aceptación del reclamo.
        <br />
        <strong><u>Dolo o mala fe</u></strong>
        <br />
        Las declaraciones falsas o reticentes hechas con dolo o mala fe hacen nulo el presente contrato. En este caso el Asegurado no tendrá derecho a la devolución de las primas pagadas.
        <br />
        <strong><u>Conciliación y Arbitraje</u></strong>
        <br />
        Se acuerda y establece que no obstante lo acordado en los términos, exclusiones provisiones y condiciones 
        contenidas en la presente Póliza, las Partes intervinientes en el presente Contrato, acuerdan que las Controversias 
        de Hecho sobre las características técnicas del presente seguro, serán resueltas a través del Peritaje; si por esta 
        vía no se llegara a un acuerdo sobre dichas controversias, estas deberán definirse por la vía del Arbitraje
        <br />
        Del mismo modo, las Controversias de Derecho suscitadas entre ellas sobre la naturaleza, alcance, interpretación o 
        ejecución de la presente Póliza o relacionadas con ella, directa o indirectamente o de sus documentos complementarios 
        o modificatorios, serán resueltas en única e inapelable instancia por la vía del Arbitraje de acuerdo a lo previsto en 
        la Ley de Conciliación y Arbitraje No. 708 del 25 de junio de 2015.
        <br />
        Las partes acuerdan que todo el proceso Arbitral, se realizara en la Camara de Comercio del lugar donde se emitió la 
        presente Póliza.  En caso de no existir un centro de Conciliación y Arbitraje de la Camara de Comercio en el Departamento 
        donde se emitió la Póliza, este se realizará conforme lo establecido en el Artículo 23 de la Ley de Conciliación y 
        Arbitraje No. 708 del 25 de junio de 2015.  Asimismo, las Partes convienen que las controversias objeto del presente 
        convenio arbitral, serán resueltas en Arbitraje en Derecho, por un Tribunal Arbitral compuesto por tres miembros.  
        Acordándose que cada una de las Partes nombrara un árbitro en el plazo de diez (10) días y debiendo entre estos Árbitros, 
        en el plazo de diez (10) días elegir el tercer Arbitro, En caso de no existir acuerdo en la designación del tercer árbitro, 
        la Autoridad Nominadora competente nombrara al tercer arbitro en el plazo de diez (10) días, conforme lo establecido 
        en el artículo 62 parágrafos II y III de la Ley de Conciliación y Arbitraje No. 708 del 25 de junio de 2015.
        <br />
        La existencia de la presente cláusula importa la renuncia de las partes a iniciar proceso judicial sobre las controversias 
        sometidas a arbitraje.
    </div>
    @if($c==1)
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        <div style="margin: 3mm 3mm 3mm 3mm;text-align: justify; font-size: 9px;">
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 7px; font-family: Arial, Helvetica, sans-serif; ">
                <tr>
                    <td style="width:70%;"><img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                    <td style="width:30%;"></td>
                </tr>
            </table>
            <div style="text-align: center;border-bottom:1px dotted #9999CC;">
                <strong>SOLO COPIA BANCO Y COMPAÑÍA</strong>
            </div>
            <br /><br />
            He recibido mi Certificado Individual de Cobertura y afiliación al Contrato de Seguro de Protección de 
            Tarjetas de Crédito y/o Debito Anual Renovable, con cuyos términos y condiciones estoy de acuerdo y declaro conocer.
            <br /><br />
            El cliente autoriza a la Compañía el reporte de los datos del presente documento a la Central de Riesgo del 
            mercado de seguros acorde a las normativas reglamentarias en vigencia.
            <br /><br />
            El “asegurado” autoriza el cargo mensual de la prima a su (s) cuenta (s) asociada a la tarjeta.
            <br />
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                <tr>
                    <td style="width:10%;"><strong>Póliza N°</strong></td>
                    <td style="width:40%; text-align:center;border-bottom:1px solid black;">
                        <strong>{{$header->policy_number}}</strong>
                    </td>
                    <td style="width:20%; text-align: right;"><strong>Certificado de Cobertura N°</strong></td>
                    <td style="width:30%; text-align:center;border-bottom:1px solid black;">
                        <strong>{{$header->issue_number}}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="width:10%;"><strong>Nombre:</strong></td>
                    <td style="width:40%; text-align:center;border-bottom:1px solid black;">
                        <strong>{{$detail->client->full_name}}</strong>
                    </td>
                    <td style="width:20%; text-align: right;"><strong>Fecha de Afiliación:</strong></td>
                    <td style="width:30%; text-align:center;">
                        <strong>{{date('d/m/Y', strtotime($header->date_issue))}}</strong>
                    </td>
                </tr>
            </table>    
            <br /><br /><br />
            <br /><br /><br />
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                <tr>
                    <td style="width:50%;"></td>
                    <td style="width:30%; text-align:center;border-bottom:1px solid black;">
                    </td>
                    <td style="width:2%;"></td>
                    <td style="width:30%; text-align:center;border-bottom:1px solid black;">
                        <strong>{{date('d/m/Y', strtotime($header->date_issue))}}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;">
                        Código de Cliente: <strong>{{$detail->client->code}}</strong>
                    </td>
                    <td style="width:30%; text-align:center;">
                        FIRMA DEL ASEGURADO
                    </td>
                    <td style="width:2%;"></td>
                    <td style="width:30%; text-align:center;">
                        Día Mes Año
                    </td>
                </tr>
                <tr>
                    <td style="width:50%;">
                        Numero de Tarjeta de Dédito: <strong>{{$detail->card_number}}</strong>
                    </td>
                    <td style="width:30%; text-align:center;">
                        C.I. {{$detail->client->full_dni}}
                    </td>
                    <td style="width:2%;"></td>
                    <td style="width:30%; text-align:center;">
                    </td>
                </tr>
            </table>
        </div>
    @endif
</div>