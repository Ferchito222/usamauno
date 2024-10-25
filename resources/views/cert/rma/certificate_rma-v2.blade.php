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
                    <strong>CERTIFICADO DE COBERTURA INDIVIDUAL A LA PÓLIZA DE SEGURO DE ROTURA DE MAQUINARIA (COLECTIVOS)</strong>
                    <br />
                    <strong>Código de Registro N° 113-9107113 2018 12 209 4001 Resolución Administrativa No.215/2019</strong>
                    <br />
                    <br />
                    <strong>PÓLIZA DE SEGURO DE ROTURA DE MAQUINARIA (COLECTIVOS) Póliza No. </strong> <u>{{$version->cert_number}}-0</u>
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
                        <td style="width:30%; border-bottom:1px solid black;text-align: center;">DIRECCION LA PAZ</td>
                        <td style="width:10%;">Teléfono:</td>
                        <td style="width:20%; border-bottom:1px solid black;text-align: center;">CALL CENTER</td>
                        <td style="width:5%;">Fax:</td>
                        <td style="width:25%; border-bottom:1px solid black;"></td>
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
                    <tr>
                        <td style="width:20%;">Vigencia:</td>
                        <td style="width:40%; ">Por toda la vigencia del crédito</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;"></td>
                        <td style="width:20%;"></td>
                    </tr>
                </table>
                <br />
                <strong>MATERIA DEL SEGURO:</strong>Maquinarias fijas (valor de reposición a nuevo) debidamente avaluados como garantías de los prestatarios del BANCO FORTALEZA S.A.
                y/o bajo custodia y/o control o por los cuales sea legalmente responsable.<br />
                <br />
                <strong>COBERTURAS A VALOR TOTAL {{ $header->currency=='USD'?'$US':$header->currency }}.</strong> <u>{{ number_format($value->insured_value, 2, ',', '.') }}</u>:
                <br />
                <br />
                Incluyendo<br />
                - Terrorismo<br />
                - Motines, Huelgas, Conmociones Civiles, Daño malicioso y Vandalismo,<br />
                - Sabotaje,<br />
                <br />
                <strong>MATERIA DEL SEGURO: </strong><br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 70%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:100%; text-align:center;border-bottom:1px solid black;">
                            {{ $value->description }}
                        </td>
                    </tr>
                </table>
                <br />
                Prima Total {{ $header->currency=='USD'?'$US':$header->currency }}: {{ number_format($header->total_premium, 2, ',', '.') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Forma de pago: Mensual
                <!--{{ $header->payment_method=='DA'?'Mensual':'Anual' }}-->
                <br />
                <br />
                <strong>OBSERVACIONES</strong><br />
                La adhesión al presente seguro por parte del asegurado es voluntaria y tiene la posibilidad de reemplazar la(s) cobertura(s) exigida(s), con otra póliza de similares características.<br />
                El presente certificado contiene un resumen de coberturas y exclusiones de la póliza de seguros que de manera completa se encuentra en poder del Tomador 
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
                En consideración a la Solicitud de Seguro presentada en forma escrita por el Asegurado, y que constituye parte integrante e indivisible de la presente Póliza, al igual que cualquier otra declaración escrita del Asegurador y al pago de la prima correspondiente, la Póliza se extiende a cubrir, en los montos, términos y condiciones aquí estipulados, las pérdidas o daños materiales causados a los bienes asegurados, siempre que los daños sucedan de forma accidental, súbita e imprevista por cualquier causa, siempre y cuando las mismas no se encuentren expresamente mencionadas en las exclusiones de la presente póliza.<br />
                <br />
                <strong>EXCLUSIONES:</strong><br />
                - Guerra, revolución, insurrección<br />
                - Actos intencionales o negligencia manifiesta del Asegurado o de sus representantes;<br />
                - Daños o pérdidas por los que el proveedor o fabricante de los bienes asegurados sea responsable legal o contractualmente;<br />
                - Lucro cesante<br />
                - Daños o pérdidas de unidades y/o máquinas utilizadas para obras subterráneas<br />
                <br />
                <strong>PROCEDIMIENTO EN CASO DE SINIESTRO:</strong><br />
                Al ocurrir un siniestro que cause pérdidas amparadas por la presente Póliza, el Asegurado tendrá la obligación de:<br />
                <ul>
                    <li>Medidas de salvaguarda o recuperación: Ejecutar todos los actos que tiendan a evitar o disminuir el daño.   Si no hay peligro en la demora, pedirá instrucciones a la Compañía y se atendrá a lo que ella le indique.  El incumplimiento de esta obligación podrá afectar los derechos del Asegurado en los términos de Ley.</li>
                    <li>Dar aviso del siniestro a la Compañía lo más pronto posible de tener conocimiento del siniestro, o como máximo dentro de diez (10) días hábiles de haberse suscitado el hecho de tránsito, salvo causa de fuerza mayor.</li>
                    <li>Denunciar el hecho a las autoridades competentes más próximas y si por las características del evento se presume intervención de personas en forma culposa o dolosa, dar parte a las autoridades policiales correspondientes.</li>
                    <li>El Asegurado conservará los bienes asegurados dañados y no podrá proceder a efectuar reparaciones en los bienes siniestrados sin autorización escrita de la Compañía, antes de que ésta o sus representantes inspeccionen los daños.</li>
                </ul>
                <br />
                <strong>IMPORTANTE:</strong><br />
                <ul>
                    <li>Una vez cancelada la prima el Asegurado podrá descargar su factura de nuestra página web <a href="http://181.188.151.74:8070/factura/login" target="_blank">http://181.188.151.74:8070/factura/login</a>.</li>
                    <li>Se otorgará cobertura mientras las primas se paguen de forma continua, el incumplimiento en el pago de la prima, dentro de los plazos fijados, suspende la vigencia de la póliza de seguro.</li>
                    <li>El presente documento resume los términos y condiciones de la póliza principal suscrito por la Compañía de Seguros y Reaseguros Fortaleza S.A. y el Tomador del seguro, el mismo que respalda este certificado y prevalece sobre cualquier otro texto informativo.</li>
                    <li>El Asegurado se adhiere voluntariamente al presente seguro, pudiendo reemplazar esta póliza en cualquier momento, con otra póliza de similares características. Para la cancelación del presente seguro el Asegurado únicamente debe presentar una carta firmada dirigida al Tomador o la Compañía solicitando su baja del seguro, misma que surtirá efecto inmediato desde la fecha de recepción de tal nota.</li>
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