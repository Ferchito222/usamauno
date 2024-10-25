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
            <strong>PÓLIZA DE SEGURO DE PROTECCION DE TARJETAS DE CREDITO</strong>
            <br />
            <strong>Código de Registro: 113-9109245-2017 01 203 4002</strong>
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
                    {{$header->prefix}} - {{$header->issue_number}}
                </td>
            </tr>
        </table>
        <br />
        Se deja expresa constancia mediante el presente certificado, que la persona Asegurada abajo señalada ha sido admitida como integrante de la Póliza de Seguros contratada por el
        Tomador.
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 80%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:40%;"><strong>ASEGURADO: (Nombre y Apellidos)</strong></td>
                <td style="width:60%; text-align:center;border-bottom:1px solid black;">
                    {{$detail->client->full_name}}
                </td>
            </tr>
        </table>
        <br />
        <strong>FECHA DE INICIO DE VIGENCIA: Por toda la vigencia de la Tarjeta de Crédito</strong>
        <br />
        <strong>TOMADOR: {{$header->retailerProduct->retailer->name}}</strong>
        <br />
        <strong>BENEFICIARIOS: </strong> <em>(Personas designadas por el Asegurado o herederos legales)</em>
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            @foreach($detail->beneficiaries as $beneficiary)
            <tr>
                <td style="width:100%; text-align:center;border-bottom:1px solid black;">{{$beneficiary->full_name}}</td>
            </tr>
            @endforeach
        </table>
        <br />
        <br />
        <strong>COBERTURAS Y CAPITALES ASEGURADOS:</strong>
        <br />
        <dl>
            <dt><strong>Protección contra Robo, Clonación o Extravío de la Tarjeta de Crédito:</strong></dt>
            <dd>- cobertura de robo y/o atraco y/o asalto y/o utilización forzada y/o fraudulenta por terceros hasta Bs. 6.960.- Por evento y Bs. 10,440.- en el agregado anual</dd>
            <dd>- Uso indebido de tarjeta por terceros: limitándose a las compras de productos y servicios realizados dentro de los primeros 5 días (a partir de la fecha del robo o extravío de la tarjeta)
                Hasta Bs. 69.600.- Por evento y agregado anual
            </dd>
            <dd>- Clonación o adulteración de bandas magnéticas de tarjetas de crédito, Hasta Bs. 69.600.- (Por evento y agregado anual)</dd>
            <dd>- Secuestro del tarjeta habiente para retiros de dinero en efectivo, Hasta Bs. 13..920.- por día Hasta Bs. 17.400.- por tarjeta en el agregado anual</dd>
            <dt><strong>Protección Personal al Asegurado:</strong></dt>
            <dd>- Muerte accidental a consecuencia de asalto Hasta Bs. 48.720.-</dd>
            <dd>
                Anexo de cobertura para invalidez total y permanente a consecuencia de Asalto Hasta Bs. 48.720.-
                Coberturas Adicionales:
            </dd>
            <dd>- Re-emisión de documentos Hasta Bs. 1.740.- (Por evento y agregado anual)</dd>
            <dd>- Transacciones realizadas con tarjetas de crédito a través de ventas por internet, hasta Bs.6,960.- .</dd>
        </dl> 
        <br />
        <strong>PRIMA TOTAL MENSUAL:</strong>
        <br />
        Prima Mensual a Facturar por el Asegurado: 14 Bolivianos por tarjeta.
        <br />
        <br />
        <strong>CONTRATO PRINCIPAL (POLIZA MATRIZ):</strong>
        <br />
        <strong>La Compañía de Seguros y Reaseguros FORTALEZA S.A.</strong>, asegura al Tarjetahabiente, según las condiciones descritas en el Contrato del seguro de protección de Tarjetas de Crédito;
        celebrado entre <strong>La Compañía de Seguros y Reaseguros FORTALEZA S.A.</strong>, y el Tomador <strong>Banco Fortaleza</strong>, condiciones que forman parte integrante del seguro aquí concedido.
        El presente Certificado de Seguro tendrá valor siempre y cuando la póliza este vigente y sus primas se encuentren pagadas de acuerdo a las Condiciones de la póliza.
        <br />
        <br />
        <strong>ADHESION VOLUNTARIA DEL ASEGURADO:</strong>
        <br />
        El Asegurado al momento de concretar el crédito con el Contratante declara su consentimiento VOLUNTARIO para ser asegurado en la póliza arriba indicada, declara conocer y estar
        de acuerdo con las condiciones del contrato de seguros.
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
                    <img src="{{ base_path('public/images/jose_eguez_rodriguez.jpg') }}" height="90px">
                </td>
                <td style="width:10%; text-align:center;"></td>
                <td style="width:25%; text-align:center;">
                    <img src="{{ base_path('public/images/luis_trigo_cuellar.jpg') }}" height="90px">
                </td>
            </tr>
            <tr>
                <td style="width:25%;">FIRMA DEL ASEGURADO</td>
                <td style="width:15%; text-align:center;"></td>
                <td style="width:25%; text-align:center;">
                    
                </td>
                <td style="width:10%; text-align:center;"></td>
                <td style="width:25%; text-align:center;">
                    
                </td>
            </tr>
        </table>
        <br />
        <br />
        <div style="text-align: center;">
            EN CASO DE SINIESTRO SIRVASE CONTACTARSE DIRECTAMENTE CON LA CIA.<br />
            <strong>Call Center 800-10-9992</strong>
        </div>
        <br />
        <br />
        He recibido mi Certificado Individual de Cobertura y afiliación al Contrato de Seguro de Protección de Tarjetas de Crédito Anual Renovable, con cuyos términos y
        condiciones estoy de acuerdo y declaro conocer.
        <br />
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:10%;">Póliza N°</td>
                <td style="width:10%; text-align:center;border-bottom:1px solid black;">
                    {{$header->prefix}} - {{$header->issue_number}}
                </td>
                <td style="width:40%;"></td>
                <td style="width:20%;">Certificado de Cobertura N°</td>
                <td style="width:20%; text-align:center;border-bottom:1px solid black;">
                    {{$header->policy_number}}
                </td>
            </tr>
        </table>
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:10%;">Nombre:</td>
                <td style="width:30%; text-align:center;border-bottom:1px solid black;">
                    {{$detail->client->full_name}}
                </td>
                <td style="width:20%;"></td>
                <td style="width:15%;">Fecha de afiliación</td>
                <td style="width:25%; text-align:center;border-bottom:1px solid black;">{{date('d/m/Y',strtotime($header->created_at))}}</td>
            </tr>
        </table>
        <br />
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:10%;"></td>
                <td style="width:30%;"></td>
                <td style="width:20%;"></td>
                <td style="width:5%;text-align: center;">{{date('d',strtotime($header->date_issue))}} /</td>
                <td style="width:5%;text-align: center;">{{date('m',strtotime($header->date_issue))}} /</td>
                <td style="width:5%;text-align: center;">{{date('Y',strtotime($header->date_issue))}} /</td>
                <td style="width:25%;"></td>
            </tr>
            <tr>
                <td style="width:10%;"></td>
                <td style="width:30%;">
                    FIRMA DEL ASEGURADO<br />
                    C.I. {{$detail->client->full_dni}}
                </td>
                <td style="width:20%;"></td>
                <td style="width:5%;border-top:1px solid black;text-align: center;">Día</td>
                <td style="width:5%;border-top:1px solid black;text-align: center;">Mes</td>
                <td style="width:5%;border-top:1px solid black;text-align: center;">Año</td>
                <td style="width:25%;"></td>
            </tr>
        </table>
        <br />
        <br />
        <br />
        <div style="text-align: center; font-weight: bold;">
            IMPORTANTE:<br />
            Una vez cancelada la prima el Asegurado podrá descargar su factura en nuestra página web http://181.188.151.74:8070/factura/login
        </div>
    </div>
    
    <page><div style="page-break-before: always;">&nbsp;</div></page>
    
    <div style="margin: 3mm 3mm 3mm 3mm;text-align: justify; font-size: 7px;">
        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 7px; font-family: Arial, Helvetica, sans-serif; ">
            <tr>
                <td style="width:70%;"><img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                <td style="width:30%;"></td>
            </tr>
        </table>
        <div style="text-align: center">
            <strong>REGLAS DEL CONTRATO DE SEGURO DE PROTECCION DE TARJETAS DE CREDITO ANUAL RENOVABLE</strong>
        </div>
        <br />
        <br />
        <strong>EXCLUSIONES GENERALES:</strong>
        <br />
        Esta Póliza no cubre:
        <br />
        <ul>
            <li>Reclamos provenientes de cualquier transacción en cajeros automáticos que no sea la Extracción de Dinero en Efectivo.</li>
            <li>Secuestro con el propósito de realizar Compras mediante el uso forzado.</li>
            <li>No cubre las pérdidas causadas al asegurado que directa o indirectamente provengan o sean a consecuencia de transacciones realizadas a través de las ventas por catálogo, por teléfono o por cualquier medio de transmisión de datos.</li>
            <li>No cubre cualquier perdida que sea atribuible o responsabilidad de VISA, MASTER CARD o cualquier otra marca de tarjetas, al Operador, Procesadora, Marcas, Establecimientos y otros, ajenos al Asegurado o al tomador de la póliza.</li>
            <li>Fraudes, Estafas y/o cualquier otro delito que cuente con la participación directa o indirecta del Asegurado, alguno de sus ascendientes o descendientes y/o parientes por afinidad hasta segundo grado.</li>
            <li>Incumplimiento de alguna de las obligaciones por parte del asegurado o usuario.</li>
            <li>
                La pérdida que el Asegurado pueda legalmente cargar a alguna de las siguientes personas o entidades, y obtener reembolso de ellas:<br />
                a) el titular de la tarjeta<br />
                b) alguna persona, firma o persona jurídica que acceda a pagar las tarjetas de crédito del Asegurado; o<br />
                c) alguna otra institución financiera, asociación de tarjetas de crédito, o cámara de compensación bancaria que represente al Asegurado.
            </li>
            <li>La pérdida de interés, o la parte de una pérdida que se deba a un descuento por parte de alguna persona, firma, o persona jurídica que acceda a pagar las tarjetas de crédito al Asegurado.</li>
            <li>La pérdida que resulte de emitir una tarjeta de crédito para garantizar el cobro de algún cheque o giro.</li>
            <li>La pérdida que resulte de acto fraudulento, deshonesto o delictivo de algún funcionario, director o empleado del tomador, ya sea que dicho funcionario, director o empleado actúe solo o en colusión con terceros.</li>
            <li>La pérdida debida al uso de una tarjeta de crédito legítima por parte de una persona no autorizada que usa su firma legítima con la intención de estafar al Asegurado.</li>
            <li>Toda pérdida indirecta, incluidos, aunque no taxativamente, el lucro cesante, el retraso, la pérdida de mercado.</li>
            <li>Toda pérdida que resulte de la falta de pago total o parcial o de la mora con relación a préstamo u operación que se asemeje o que ascienda a un préstamo hecho por el Asegurado u obtenido de él.</li>
            <li>Toda responsabilidad legal de cualquier naturaleza.</li>
            <li>Toda pérdida no descubierta durante la vigencia indicada en las Condiciones Particulares.</li>
            <li>
                Toda pérdida que directa o indirectamente surja debido o con relación a terrorismo, guerra, invasión, acto de enemigo extranjero, hostilidades u operaciones belicosas (ya sea que medie declaración de guerra o no), guerra civil, rebelión, revolución, sedición, insurrección, conmoción civil que adquiera proporciones de sublevación popular o equivalga a ella, poder militar o usurpado, ley marcial, tumulto, acto de alguna autoridad legalmente constituida, confiscación, nacionalización, requisición hecha u ordenada por cualquier gobierno o autoridad pública, nacional o local.
            </li>
            <li>
                Toda pérdida directa o indirectamente causada por los siguientes factores, o emergente de ellos:<br />
                (i) radiaciones ionizantes o contaminación producida por la radiactividad emanada de combustible nuclear o de desechos nucleares provenientes de la combustión de combustible nuclear.<br />
                (ii) las propiedades radiactivas, tóxicas, explosivas u otras propiedades riesgosas de algún conjunto nuclear explosivo, o de componente nuclear de dicho conjunto.
            </li>
            <li>Toda pérdida que resulte de responsabilidad civil, responsabilidad consecuencial, así como el lucro cesante.</li>
            <li>Suicidio, independientemente del estado mental de la persona, lesiones intencionalmente ocasionadas asimismo por el Asegurado.</li>
            <li>Enfermedad, padecimiento o infección bacterias de cualquier tipo, a excepción de infecciones que surgen a consecuencia de una lesión accidental ocurridas en el asalto.</li>
            <li>Hernia, independientemente de la causa.</li>
            <li>Actos delictivos o cualquier intento de los mismos por pate del Asegurado.</li>
            <li>Cirugías cosméticas o procedimientos cosméticos a excepción de cirugía reconstructiva para la corrección de un desorden físico originado a consecuencia del robo y/o atraco y/o asalto cubierta bajo la presente póliza.</li>
            <li>Siniestros ocurridos fuera del territorio nacional</li>
        </ul>
        <br />
        <strong>PROCEDIMIENTO EN CASO DE SINIESTRO:</strong>
        <br />
        Al ocurrir un siniestro que cause pérdidas amparadas por la presente Póliza, el tomador de la póliza deberá dar aviso del siniestro a la compañía, a más tardar dentro de los 3 días de conocido el siniestro, salvo fuerza mayor o impedimento debidamente justificado.
        <br />
        <br />
        El asegurador puede liberarse de sus obligaciones cuando se omita dar el aviso del siniestro, con el fin de impedir la comprobación oportuna de las circunstancias del siniestro o el de la magnitud de los daños
        Asimismo, Al ocurrir un siniestro que cause pérdidas amparadas por la presente Póliza, el Asegurado tendrá la obligación de:
        <br />
        <ol type="i">
            <li>Para el evento de Robo, Atraco, Asalto y/o utilización forzada y/o fraudulenta por terceros: Realizar la denuncia pertinente a la autoridad competente (policial o judicial) en el plazo de 24 horas siguientes a la ocurrencia o de tener conocimiento o indicios de la pérdida o posible pérdida.</li>
            <li>Comunicar tal hecho a la entidad emisora de la tarjeta por escrito, mediante el formulario de Denuncia del Siniestro, inmediatamente y máximo dentro de los tres días de sucedido, salvo fuerza mayor o impedimento justificado, en cuyo caso deberá dar tal aviso inmediatamente después de tal impedimento o fuerza mayor.</li>
            <li>Solicitar el bloqueo de tarjeta a la Administradora de Tarjetas al momento de descubrir o tener indicios de la pérdida.</li>
            <li>Llenar el Formulario de Denuncia del Siniestro, en este debe especificarse el importe reclamado y la fecha de ocurrencia del siniestro.</li>
            <li>Responder a la Compañía Aseguradora sobre los requerimientos de documentación e información adicional para la evaluación del reclamo, que razonablemente pueda ser proporcionada por el Asegurado y/o el tomador.</li>
        </ol>
        <br />
        Posteriormente el tomador recibirá la denuncia del siniestro de su cliente y recabará la información necesaria sobre el hecho, para posteriormente enviar y solicitar su evaluación a la Compañía Aseguradora, adjuntando al mismo lo siguiente:
        <br />
        <ol>
            <li>Formulario de Denuncia de Siniestro debidamente llenado.</li>
            <li>Reporte de solicitud de bloqueo de la tarjeta.</li>
            <li>Para el caso de Robo, Atraco, Asalto, y/o Utilización forzada y/o fraudulenta por terceros, Denuncia a la autoridad competente (policial y/o judicial).</li>
            <li>Extracto de los últimos movimientos de la cuenta (últimos 6 meses).</li>
            <li>De existir información adicional se la debe detallar expresamente.</li>
        </ol>
        <br />
        La Compañía Aseguradora dentro el plazo de 30 días calendario evaluará el reclamo y procederá con:
        <br />
        <ol type="a">
            <li>La liquidación del reclamo, cuando así corresponda.</li>
            <li>La solicitud de información adicional, cuando el caso así lo amerite.</li>
            <li>El rechazo fundamentado del reclamo.</li>
        </ol>
        <br />
        <strong>IMPORTANTE:</strong>
        <br />
        Se otorgará cobertura mientras las primas se paguen de forma continua, el incumplimiento en el pago de la prima, dentro de los plazos fijados, suspende la vigencia de la póliza de seguro.<br />
        El presente documento resume los términos y condiciones de la póliza principal suscrito por la Compañía de Seguros y Reaseguros Fortaleza S.A. y el ({{$header->retailerProduct->retailer->name}}), el mismo que respalda este certificado y prevalece sobre cualquier otro texto informativo.
        <br />
        <br />
        <br />
        <div style="text-align: center">
            <strong>COMPAÑÍA DE SEGUROS Y REASEGUROS FORTALEZA S.A.</strong>
        </div>
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
                        <strong>{{$header->prefix}} - {{$header->issue_number}}</strong>
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
                        Numero de Tarjeta de Crédito: <strong>{{$detail->card_number}}</strong>
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