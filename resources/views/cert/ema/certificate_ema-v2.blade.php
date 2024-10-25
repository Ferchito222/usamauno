@var $version = false
@if($header->ad_certificate_id)
    @var $version = $header->versionCertificates()->where('ad_city_id',$header->user->city->id)->first()
@endif
<div style="width: 710px; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
    @var $copies = 3;
    @for($c = ($copies == 1 ? 1 : $copies - 2); $c <= $copies; $c++)
        @foreach($header->details as $value)
            <div style="margin: 8mm 8mm 8mm 8mm;text-align: justify;">
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:70%;"><img src="{{ asset($header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                        <td style="width:5%; text-align:right;"><br /><br /><strong>No. </strong></td>
                        <td style="width:25%; text-align:center;border-bottom:1px solid black;">
                            <br /><br />{{ $version->cert_number }}-{{ str_pad($header->issue_number, 4, 0, STR_PAD_LEFT) }}
                        </td>
                    </tr>
                </table>
                <br />
                <div style="text-align: center">
                    <strong>CERTIFICADO DE COBERTURA INDIVIDUAL A LA PÓLIZA DE SEGURO DE EQUIPO Y MAQUINARIA DE CONTRATISTAS (COLECTIVOS)</strong>
                    <br />
                    <strong>Código de Registro N°  113-9107114-2019 03 211 4001 Resolución Administrativa No. 461/2019</strong>
                    <br />
                    <br />
                    <strong>PÓLIZA DE SEGURO DE EQUIPO Y MAQUINARIA DE CONTRATISTAS - Póliza No. </strong><u>{{$version->cert_number}}-0</u>
                </div>
                <br />
                Asegurador: {{ $header->retailerProduct->companyProduct->company->name }}<br />
                Dirección: Av. Virgen de Cotoca No. 2080 – Santa Cruz<br />
                Tel: (591-3) 3487273<br />
                Fax: (591-3) 3497675<br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 60%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:25%;">Corredor de Seguros:</td>
                        <td style="width:75%; border-bottom:1px solid black;text-align: center;">SUDAMERICANA SEGUROS S.A.</td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 80%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:10%;">Dirección:</td>
                        <td style="width:30%; border-bottom:1px solid black;text-align: center">DIRECCION LA PAZ</td>
                        <td style="width:10%;">Teléfono:</td>
                        <td style="width:35%; border-bottom:1px solid black;text-align: center;">CALL CENTER SUDAMERICANA</td>
                        <td style="width:5%;">Fax:</td>
                        <td style="width:10%; border-bottom:1px solid black;"></td>
                    </tr>
                </table>
                <br />
                <div style="text-align: center;">
                    <strong>Datos Generales del Asegurado</strong>
                </div>
                <br />
                Tomador: BANCO FORTALEZA S.A.<br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:20%;">{{($header->client->type == 'N')?'Nombre del Asegurado':'Nombre o Razón Social' }}:</td>
                        <td style="width:40%; border-bottom:1px solid black;">{{ $header->client->full_name }}</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;">{{($header->client->type == 'N')?'Carnet de Identidad':'NIT'}}:</td>
                        <td style="width:20%; border-bottom:1px solid black;">{{ ($header->client->type == 'N')?$header->client->full_dni:$header->client->dni }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">{{($header->client->type == 'N')?'Dirección':'Dirección Principal'}}:</td>
                        <td style="width:40%; border-bottom:1px solid black;">{{ $header->client->home_address }}</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;">{{($header->client->type == 'N')?'Teléfono':'Teléfono Fijo 1'}}:</td>
                        <td style="width:20%; border-bottom:1px solid black;">{{ $header->client->phone_number_home }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;">Lugar de Emisión:</td>
                        <td style="width:40%; border-bottom:1px solid black;">{{ $header->user->city->name }}</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;">Fecha de Emisión:</td>
                        <td style="width:20%; border-bottom:1px solid black;">{{ date('d/m/Y', strtotime($header->date_issue)) }}</td>
                    </tr>
                </table>
                Vigencia: Por toda la vigencia del crédito
                <br />
                <br />
                <strong>MATERIA DEL SEGURO:</strong> Equipos y maquinarias de contratista (nuevos o usados) debidamente avaluados como garantías de los prestatarios del BANCO FORTALEZA S.A. y/o  bajo custodia y/o control o por los cuales sea legalmente responsable.<br />
                <br />
                <strong>SUMA ASEGURADA {{ $header->currency=='USD'?'$US':$header->currency }}.</strong> <u>{{ number_format($value->insured_value, 2, ',', '.') }}</u>: Equipo y maquinaria de contratistas,
                <br />
                <br />
                Incluyendo<br />
                - Transportes Nacionales <br />
                - Terrorismo,<br />
                - Motines, Huelgas, Conmociones Civiles, Daño malicioso y Vandalismo, Sabotaje<br />
                <br />

                <strong>COBERTURAS A PRIMER RIESGO:</strong><br />
                <ul>
                    <li>Responsabilidad Civil Extracontractual &nbsp;&nbsp;&nbsp; Hasta $us.10.000.-</li>
                </ul>
                <br />
                Prima Total {{ $header->currency=='USD'?'$US':$header->currency }}: {{ number_format($header->total_premium, 2, ',', '.') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Forma de pago: Mensual
                <br />
                <!--
                <table cellpadding="0" cellspacing="0" border="0" style="width: 70%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:30%;"><strong>MATERIA DEL SEGURO: </strong></td>
                        <td style="width:70%;border-bottom:1px solid black;">
                            {{ $value->description }}
                        </td>
                    </tr>
                </table>-->
                
                <!--{{ $header->payment_method=='DA'?'Mensual':'Anual' }}-->
                <br />
                <br />
                <strong>OBSERVACIONES</strong><br />
                La adhesión al presente seguro por parte del asegurado es voluntaria y tiene la posibilidad de reemplazar la(s) cobertura(s) exigida(s), con otra póliza de similares características. <br />
                El presente certificado contiene un resumen de coberturas y exclusiones de la póliza de seguros que de manera completa se encuentra en poder del Tomador .
                <br /><br /><br /><br /><br /><br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:40%; border-bottom:1px solid black;">

                        </td>
                        <td style="width:10%;"></td>
                        <td style="width:50%; border-bottom:1px solid black;text-align: center;">
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                                <tr>
                                    <td style="width:50%;text-align: center;">
                                        <img src="{{ asset('/images/firma_garantias-v2/'.$version->firma_a) }}" height="90px"><br />
                                        {{$version->pie_a}}<br />
                                        @if($header->user->city->abbreviation != 'SC')
                                            FORTALEZA S.A.
                                        @endif
                                    </td>
                                    <td style="width:50%;text-align: center;">
                                        <img src="{{ asset('/images/firma_garantias-v2/'.$version->firma_b) }}" height="90px"><br />
                                        {{$version->pie_b}}<br />
                                        FORTALEZA S.A.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%; text-align: center;">Firma - Asegurado</td>
                        <td style="width:10%;"></td>
                        <td style="width:50%; text-align: center;">Firma Autorizada</td>
                    </tr>
                </table>
            </div>

            <page><div style="page-break-before: always;">&nbsp;</div></page>

            <div style="margin: 8mm 8mm 8mm 8mm;text-align: justify;">
                <strong>OBJETO DEL SEGURO</strong><br />
                En consideración a la Solicitud de Seguro presentada en forma escrita por el Asegurado, y que constituye parte integrante e indivisible de la presente Póliza, al igual que cualquier otra declaración escrita del Asegurador y al pago de la prima correspondiente, la Póliza se extiende a cubrir, en los montos, términos y condiciones aquí estipulados, las pérdidas o daños materiales causados a los bienes asegurados o partes de los mismos, mientras se encuentren dentro del sitio asegurado o dentro del área geográfica allí especificada, a consecuencia de un siniestro accidental, súbito e imprevisto originado por cualquier causa que fuera (salvo las excluidas expresamente) en forma tal que exijan su reparación o reposición.<br />
                <br />
                <strong>EXCLUSIONES:</strong><br />
                - Guerra, revolución, insurrección<br />
                - Actos intencionales o negligencia manifiesta del Asegurado o de sus representantes;<br />
                - Daños o pérdidas por los que el proveedor o fabricante de los bienes asegurados sea responsable legal o contractualmente;<br />
                - Lucro cesante<br />
                - Daños o pérdidas de unidades y/o máquinas utilizadas para obras subterráneas<br />
                - Daños por reacción nuclear, contaminación nuclear o contaminación radioactiva<br />
                - Daños por falla o defectos que ya existían en el momento de contratarse el presente seguro y eran conocidos por el Asegurado, aunque los Aseguradores hubiera o no tenido conocimiento de tales fallas o efectos<br />
                <br />
                <strong>PROCEDIMIENTO EN CASO DE SINIESTRO:</strong><br />
                Al ocurrir un siniestro que pudiera dar lugar a una reclamación, el Asegurado tendrá la obligación de:<br />
                <ul type="a">
                    <li>Dar aviso del siniestro a la Compañía inmediatamente, o como máximo dentro de los tres (3) días calendario de conocido el siniestro, por cualquier medio y formalizarlo por escrito, salvo causa de fuerza mayor o impedimento debidamente justificado.</li>
                    <li>El Asegurador puede liberarse de sus obligaciones cuando el asegurado o beneficiario, según el caso, omitan dar aviso dentro del plazo establecido, con el fin de impedir la comprobación oportuna de las circunstancias del siniestro o el de la magnitud de los daños.</li>
                    <li>tomar todas las medidas, dentro de sus posibilidades, para aminorar a un mínimo la extensión de la pérdida o daño;</li>
                    <li>conservar las partes dañadas y ponerlas a disposición de un representante o experto de los Aseguradores para su inspección;</li>
                    <li>suministrar toda aquella información y pruebas documentales que los Aseguradores le requieran;</li>
                    <li>informar a las autoridades correspondientes en caso de pérdida o daño debidos a robo.</li>
                </ul>
                <br />
                <br />
                <strong>DISCREPANCIAS EN LA POLIZA</strong><br />
                Si el Tomador o Asegurado encuentra que la Póliza no concuerda con lo convenido o con lo propuesto, pueden pedir la rectificación correspondiente, por escrito, dentro de los quince días siguientes a la recepción de la misma.  Se considerarán aceptadas las estipulaciones de este si durante dicho plazo no se solicita la mencionada rectificación.
                <br />
                CONCILIACION Y ARBITRAJE
                <br />
                Las partes intervinientes acuerdan que toda controversia, discrepancia, cuestión o reclamación resultante de la ejecución o interpretación del presente contrato o relacionado con él, directa o indirectamente o de sus documentos complementarios o modificatorios, será resuelto definitivamente conforme la Ley 708 de 25 de junio de 2015 de Conciliación y Arbitraje, siendo la Conciliación una vía alternativa sujeta a la voluntad de las partes, la cual en caso de ser considerada por las partes, se sustanciara ante el Centro de Conciliación y Arbitraje o Centros de Arbitraje de la Camara de Industria, Comercio, Servicios y Turismo en la ciudad donde fue emitida la póliza de la cual se sujetara la controversia.
                <br />
                Asimismo, las partes de plena y espontanea voluntad acuerdan que, de no llegar a una conciliación que de fin a la controversia o no haber considerado la vía conciliatoria para la solución de los conflictos de partes, y/o desistimiento la vía conciliatoria, podrán acudir en forma directa al proceso de Arbitraje, el cual se sustanciara como Arbitraje en Derecho al ampao del Art. 40 parágrafo I de la Ley 708 de Conciliación y Arbitraje.
                <br />
                Para el caso de Arbitraje, se conviene expresamente que el Tribunal estará formado por tres árbitros, cada parte nombrar a uno y el tercero deberá ser asignado por ellos mismos de mutuo acuerdo, en caso de desacuerdo para el nombramiento del tercer árbitro, este será designado por la Autoridad Nominadora en sujeción al Art. 62, III de la Ley 708.
                Las partes acuerdan que la sede de la Conciliación y Arbitraje será del Centro de Conciliación y Arbitraje de la Camara de Industria, Comercio, Servicios y Turismo en la ciudad donde fue emitida la póliza a la cual se sujetara la controversia.
                <br />
                <br />
                <strong>IMPORTANTE:</strong><br />
                <ul>
                    <li>Una vez cancelada la prima el Asegurado podrá descargar su factura de nuestra página web <a href="http://181.188.151.74:8070/factura/login" target="_blank">http://181.188.151.74:8070/factura/login</a>.</li>
                    <li>Se otorgará cobertura mientras las primas se paguen de forma continua, el incumplimiento en el pago de la prima, dentro de los plazos fijados, suspende la vigencia de la póliza de seguro.</li>
                    <li>El presente documento resume los términos y condiciones de la póliza principal suscrito por la Compañía de Seguros y Reaseguros Fortaleza S.A. y el Tomador del seguro, el mismo que respalda este certificado y prevalece sobre cualquier otro texto informativo.</li>
                    <li>El Asegurado se adhiere voluntariamente al presente seguro, pudiendo reemplazar esta póliza en cualquier momento. Para la cancelación del presente seguro el Asegurado únicamente debe presentar una carta firmada dirigida al Tomador o la Compañía solicitando su baja del seguro, misma que surtirá efecto inmediato desde la fecha de recepción de tal nota.</li>
                    <li>Las partes reconocen que las condiciones del Seguro y la póliza, pueden cambiar y que en todos los casos el Asegurado debe informarse sobre los cambios o modificaciones en la cobertura de los seguros contratados por el Tomador.</li>
                </ul>
                <br /><br /><br /><br /><br />
                <div style="text-align: center;">
                    <strong>COMPAÑÍA DE SEGUROS Y REASEGUROS FORTALEZA S.A.</strong>
                </div>
            </div>
        @endforeach    
        @if($c < $copies)
                <page>
                    <div style="page-break-before: always;">&nbsp;</div>
                </page>
        @endif
    @endfor 
</div>