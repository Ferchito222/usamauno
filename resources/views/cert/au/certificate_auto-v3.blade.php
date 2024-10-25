@var $version = false
@if($header->ad_certificate_id)
    @var $version = $header->versionCertificates()->where('ad_city_id',$header->user->city->id)->first()
@endif
<div style="width: 710px; font-weight: normal; font-size: 9px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
    @var $copies = 3;
    @for($c = ($copies == 1 ? 1 : $copies - 2); $c <= $copies; $c++)
        @var $i = 1
        @foreach($header->details as $value)
            @var $vehicle_type = $value->vehicleType->category->category.$value->use
            @if($i > 1)
                <page><div style="page-break-before: always;">&nbsp;</div></page>
            @endif
            <div style="margin: 8mm 8mm 8mm 8mm;text-align: justify;">
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:70%;"><img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                        <td style="width:5%; text-align:right;"><br /><br /><strong>No. </strong></td>
                        <td style="width:25%; text-align:center;border-bottom:1px solid black;">
                            <br /><br />{{$version->cert_number}}-{{ str_pad($header->issue_number, 4, 0, STR_PAD_LEFT) }}
                        </td>
                    </tr>
                </table>
                <br />
                <div style="text-align: center">
                    <strong>CERTIFICADO DE COBERTURA INDIVIDUAL – SEGURO DE AUTOMOTORES (COLECTIVOS)</strong>
                    <br />
                    <strong>Código de Registro N° 113-910557-2018 12 210 4001 Resolución Administrativa No. 1733/2018</strong>
                    <br />
                    <br />
                    <strong>POLIZA DE SEGURO DE AUTOMOTORES (COLECTIVOS)- Póliza No. </strong> <u>{{$version->cert_number}}-0</u>
                </div>
                <br />
                Asegurador: {{ $header->retailerProduct->companyProduct->company->name }}<br />
                Dirección: Av. Virgen de Cotoca No. 2080 – Santa Cruz<br />
                Tel: (591-3) 3487273<br />
                Fax: (591-3) 3497675<br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 60%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:25%;">Corredor de Seguros:</td>
                        <td style="width:75%; border-bottom:1px solid black;text-align: center;">SUDAMERICANA SEGUROS S.R.L.</td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 90%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:10%;">Dirección:</td>
                        <td style="width:40%; border-bottom:1px solid black;text-align: center;">Prolongación Cordero N° 163 - San Jorge, La Paz </td>
                        <td style="width:10%;">Teléfono:</td>
                        <td style="width:20%; border-bottom:1px solid black; text-align: center;">800-10-3070</td>
                        <td style="width:5%;">Fax:</td>
                        <td style="width:15%; border-bottom:1px solid black;"></td>
                    </tr>
                </table>
                <br />
                <strong>DATOS GENERALES DEL ASEGURADO</strong><br />
                Tomador: BANCO FORTALEZA S.A.<br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:20%;">{{($header->client->type == 'N')?'Nombre del Asegurado':'Nombre o Razón Social' }}:</td>
                        <td style="width:40%; border-bottom:1px solid black; text-transform: uppercase;">
                            @if($header->client->type == 'N')
                                {{ $header->client->first_name }} 
                                {{ $header->client->last_name }} 
                                {{ $header->client->mother_last_name }} 
                                @if($header->client->gender === 'F' && ! empty($header->client->married_name))
                                    @if($header->client->civil_status === 'C')
                                        de {{ $header->client->married_name }}
                                    @elseif($header->client->civil_status === 'V')
                                        vda. de {{ $header->client->married_name }}
                                    @endif
                                @endif
                            @else
                                {{ $header->client->full_name }}
                            @endif
                        </td>
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
                        <td style="width:20%; border-bottom:1px solid black;">{{ $header->date_issue }}</td>
                    </tr>
                </table>
                Vigencia: Por toda la vigencia del crédito<br />
                <br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 80%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:40%;"><strong>MATERIA DEL SEGURO:</strong></td>
                        <td style="width:20%;"></td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;"><strong>USO:</strong></td>
                        <td style="width:20%;"></td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 80%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:20%;"><strong>Tipo:</strong></td>
                        <td style="width:40%; border-bottom:1px solid black;">{{ $value->vehicleType->vehicle }}</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;"><strong>Marca:</strong></td>
                        <td style="width:20%; border-bottom:1px solid black;">{{ $value->brand }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;"><strong>Modelo:</strong></td>
                        <td style="width:40%; border-bottom:1px solid black;">{{ $value->model }}</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;"><strong>Placa:</strong></td>
                        <td style="width:20%; border-bottom:1px solid black;">{{ $value->license_plate }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;"><strong>Color:</strong></td>
                        <td style="width:40%; border-bottom:1px solid black;">{{ $value->color }}</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;"><strong>Potencia:</strong></td>
                        <td style="width:20%; border-bottom:1px solid black;">{{ $value->traction }}</td>
                    </tr>
                    <tr>
                        <td style="width:20%;"><strong>Chasis:</strong></td>
                        <td style="width:40%; border-bottom:1px solid black;">{{ $value->chassis }}</td>
                        <td style="width:2%;"></td>
                        <td style="width:18%;"><strong>Motor:</strong></td>
                        <td style="width:20%; border-bottom:1px solid black;">{{ $value->engine }}</td>
                    </tr>
                </table>
                <br />
                <strong>MARCAR CON UNA (X)</strong><br /><br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:18%;"><strong>LIVIANO PARTICULAR</strong></td>
                        <td style="width:6%; border-bottom:1px solid black; text-align: center">
                            {{$vehicle_type == 'CAPR'?'X':''}}
                        </td>
                        <td style="width:18%;"><strong>LIVIANO PUBLICO</strong></td>
                        <td style="width:6%; border-bottom:1px solid black; text-align: center">
                            {{$vehicle_type == 'CAPU'?'X':''}}
                        </td>
                        <td style="width:18%;"><strong>PESADO PARTICULAR</strong></td>
                        <td style="width:6%; border-bottom:1px solid black; text-align: center">
                            {{$vehicle_type == 'CBPR'?'X':''}}
                        </td>
                        <td style="width:18%;"><strong>PESADO PUBLICO</strong></td>
                        <td style="width:6%; border-bottom:1px solid black; text-align: center">
                            {{$vehicle_type == 'CBPU'?'X':''}}
                        </td>
                    </tr>
                </table>
                <br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:30%;"><strong>VALOR DEL VEHICULO</strong></td>
                        <td style="width:5%;"><strong>{{ $header->currency=='USD'?'$US':$header->currency }}.</strong></td>
                        <td style="width:65%; border-bottom:1px solid black;">{{ number_format($value->insured_value, 2, ',', '.') }}</td>
                    </tr>
                </table>
                <br />
                @include('cert.au.coverages')
                <br />
                <strong>PRIMA TOTAL {{ $header->currency=='USD'?'$US':$header->currency }}: {{ number_format($header->total_premium, 2, ',', '.') }}</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>FORMA DE PAGO: Mensual
                    <!--{{ $header->payment_method=='DA'?'Mensual':'Anual' }}-->
                </strong><br />
                <br />
                <strong>OBSERVACIONES</strong><br />
                La adhesión al presente seguro por parte del asegurado es voluntaria y tiene la posibilidad de reemplazar la(s) cobertura(s) exigida(s), con otra póliza de similares características.
                El presente certificado contiene un resumen de coberturas y exclusiones de la póliza de seguros que de manera completa se encuentra en poder del Tomador.
                <br /><br />
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                    <tr>
                        <td style="width:30%; border-bottom:1px solid black;">

                        </td>
                        <td style="width:10%;"></td>
                        <td style="width:60%; border-bottom:1px solid black;text-align: center;">
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 9px; font-family: Arial, Helvetica, sans-serif; ">
                                <tr>
                                    <td style="width:50%;text-align: center;">
                                        <img src="{{ base_path('public/images/firma_garantias-v3/'.$version->firma_a) }}" height="90px"><br />
                                        {{$version->pie_a}}<br />
                                        FORTALEZA S.A.
                                    </td>
                                    <td style="width:50%;text-align: center;">
                                        <img src="{{ base_path('public/images/firma_garantias-v3/'.$version->firma_b) }}" height="90px"><br />
                                        {{$version->pie_b}}<br />
                                        FORTALEZA S.A.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:30%; text-align: center;">Firma - Asegurado</td>
                        <td style="width:10%;"></td>
                        <td style="width:60%; text-align: center;">Firma Autorizada</td>
                    </tr>
                </table>
            </div>
            <page><div style="page-break-before: always;">&nbsp;</div></page>
            <div style="margin: 1mm 1mm 1mm 1mm;text-align: justify;font-size: 9px;">
                <strong>EXCLUSIONES: Genéricas para todos las secciones</strong><br />
                - Hechos provocados intencionalmente por el Asegurado y/o por el conductor del vehículo asegurado. - Multas, sanciones y penalidades impuestas por autoridades judiciales o administrativas. - Lucro cesante o cualquier otra pérdida de ganancias. - Los daños materiales y/o corporales como consecuencia de las Infracciones de primer grado señaladas en el Articulo 140 del Código de Tránsito. - Las coberturas de Responsabilidad Civil y Accidentes Personales, se aplicarán en exceso de los límites del SOAT. - Daños causados directa o indirectamente por guerra o invasión de enemigo extranjero, guerra civil, revolución, rebelión, insurrección, invasión, motín, asonada, sedición, requisición, nacionalización, ley marcial u otros hechos que atenten contra la seguridad del país, aunque no sean a mano armada, o por cualquier autoridad civil o militar legítima o de facto.<br />
                <strong>Sección I – Responsabilidad Civil Extracontractual</strong><br />
                - Lesiones corporales, muerte o daños materiales causados a los dependientes o empleados del asegurado, personas de quienes es responsable, socios, cónyuge, parientes por consanguinidad o afinidad, hasta el cuarto y segundo grado respectivamente (4to. consanguinidad: primo hermano; 2do. afinidad: cuñado). -Muerte, lesiones o daños causados por la carga transportada por el vehículo asegurado. - Daños al propio vehículo asegurado. -Muerte o lesiones a personas que en el momento del accidente se encontraran reparando o atendiendo el mantenimiento o servicio del vehículo<br />
                <strong>Sección II – Pérdida Total por Robo</strong><br />
                - La pérdida o robo del vehículo asegurado mientras se encuentre en el extranjero, si no está autorizado específicamente por la Compañía.<br />
                <strong>Sección III – Pérdida Total por Accidente</strong><br />
                - Participación en apuestas, desafíos, competencias, carreras o concursos de cualquier naturaleza, o en las pruebas preparatorias de los mismos. - La falla o rotura mecánica, los desperfectos eléctricos; los deterioros debido a desgaste por uso, sobrecarga, sobre-esfuerzo o la inadecuada conservación y mantenimiento del vehículo. - Los daños a los objetos de carga transportados, o al ser cargado o descargado con tales objetos.<br />
                <strong>Sección IV – Daños Propios</strong><br />
                - Participación en apuestas, desafíos, competencias, carreras o concursos de cualquier naturaleza, o en las pruebas preparatorias de los mismos. -La falla o rotura mecánica, los desperfectos eléctricos; los deterioros debido a desgaste por uso, sobrecarga, sobre-esfuerzo o la inadecuada conservación y mantenimiento del vehículo. -Los daños a los objetos de carga transportados, o al ser cargado o descargado con tales objetos. - Los daños o quemaduras producidas por artefactos eléctricos, fósforos, cenizas de tabaco o por combustibles o inflamables. -El transporte de mercancías azarosas, inflamables o explosivas.<br />
                <strong>Sección V – Conmoción Civil, Huelgas y Daño Malicioso</strong><br />
                Pérdida o daños sufridos, cuando en caso de conmoción civil o huelgas, el asegurado en conocimiento del peligro se exponga temerariamente a sufrir daños.<br />
                <strong>Sección VI – Robo Parcial</strong><br />
                La pérdida o robo de las partes y/o piezas del vehículo asegurado cuando no se encuentre circulando por sus propios medios, (sobre camión, plataforma de ferrocarril y otro similar, y/o remolcado). <br />
                <strong>Sección VII – Accidentes Personales a ocupantes de vehículos:</strong><br />
                - Los accidentes debidos a intoxicaciones producidas por gases (monóxido de carbono u otros), provenientes del mal estado de conservación del vehículo. Los daños personales a ocupantes que, en el momento del siniestro, se encuentren en la carrocería y no en la cabina.<br />
                
                <strong>OBLIGACIONES DEL ASEGURADO EN CASO DE SINIESTRO:</strong><br />
                En caso de ocurrir un siniestro que pudiera estar cubierto por la Póliza, el Asegurado se obliga a:<br />
                <ul>
                    <li>Tomar todas las precauciones que estén a su alcance para proteger el vehículo siniestrado y evitar la agravación y propagación de los daños materiales y/o personales.</li>
                    <li>Denunciar inmediatamente, en el mismo día, el hecho a la Autoridad Competente, en la jurisdicción donde se produjo el hecho, oficializando todas las circunstancias y detalles del 
                        mismo.</li>
                    <li>Someterse a la prueba de dosaje etílico, aun cuando dichas autoridades no se lo requieran.</li>
                    <li>Solicitar y obtener la copia legalizada del Informe Técnico Circunstancial (Denuncia, Informe Técnico, y/o Resolución del Juzgado de Tránsito con determinación de responsabilidades) sobre el siniestro, que deberá ser entregado a la Compañía en el menor plazo posible. Dicho informe debe establecer los siguientes aspectos:<br />
                        
                        - Un relato minucioso del hecho mencionado, y las circunstancias del mismo<br />
                        - Día, hora y lugar del hecho<br />
                        - Nombre, teléfono, dirección, y número de licencia de los conductores involucrados<br />
                        - Nombre, teléfono y dirección de los testigos, si los hubiera, y de las personas que hubieran sido afectadas por el siniestro<br />
                        - Todos los documentos judiciales y/o extrajudiciales relativos al caso, y todo lo que pueda contribuir al esclarecimiento del mismo<br />
                        - Test de alcoholemia, excepto para robo parcial<br />
                    </li>
                    <li>Dar aviso del siniestro a la Compañía lo más pronto posible de tener conocimiento del siniestro, o como máximo dentro de los diez (10) días hábiles de haberse suscitado el hecho 
                        de tránsito, salvo causa de fuerza mayor.</li>
                    <li>Esperar la autorización de la Compañía para iniciar la reparación de cualquier daño, salvo en caso de que el Asegurado se hallase de viaje con el vehículo, o en un lugar donde no existan agentes de la Compañía, y siempre que tal reparación fuera imprescindible para que el vehículo pueda funcionar. En tal caso el Asegurado quedaría autorizado a efectuar reparaciones que no sobrepasen por ningún motivo los CIENTO CINCUENTA ($US. 150.-) DÓLARES AMERICANOS.</li>
                    <li>Comunicar e informar sobre cualquier acuerdo judicial o extrajudicial, el mismo que deberá hacerse con el previo consentimiento y participación de la Compañía; caso contrario tal acuerdo no compromete la responsabilidad de la Compañía.</li>
                    <li>Informar y remitir inmediatamente a la Compañía, y a más tardar dentro de las 48 horas siguientes a la recepción de los mismos, todos los avisos, citaciones, requerimientos, cartas, emplazamientos y, en general, todos los documentos judiciales y/o extrajudiciales que con motivo de un siniestro cubierto por el seguro le sean dirigidos.</li>
                </ul>
                El incumplimiento de las obligaciones señaladas en la presente Cláusula por parte del Asegurado, o las acciones del mismo tendientes a modificar, ocultar o alterar la verdad sobre los hechos producidos liberará a la Compañía de toda responsabilidad con relación al siniestro, el mismo que no será indemnizable
                <br />
                <strong>IMPORTANTE:</strong>
                <ul>
                    <li>Una vez cancelada la prima el Asegurado podrá descargar su factura de nuestra página web <a href="http://181.188.151.74:8070/factura/login" target="_blank">http://181.188.151.74:8070/factura/login</a> .</li>
                    <li>Se otorgará cobertura mientras las primas se paguen de forma continua, el incumplimiento en el pago de la prima, dentro de los plazos fijados, suspende la vigencia de la póliza de seguro.</li>
                    <li>El presente documento resume los términos y condiciones de la póliza principal suscrito por la Compañía de Seguros y Reaseguros Fortaleza S.A. y el Tomador del seguro, el mismo que respalda este certificado y prevalece sobre cualquier otro texto informativo.</li>
                    <li>El Asegurado se adhiere voluntariamente al presente seguro, pudiendo reemplazar esta póliza en cualquier momento. Para la cancelación del presente seguro el Asegurado únicamente debe presentar una carta firmada dirigida al Tomador o la Compañía solicitando su baja del seguro, misma que surtirá efecto inmediato desde la fecha de recepción de tal nota.</li>
                    <li>Las partes reconocen que las condiciones del Seguro y la póliza, pueden cambiar y que en todos los casos el Asegurado debe informarse sobre los cambios o modificaciones en la cobertura de los seguros contratados por el Tomador.</li>
                </ul>
                <br />
                <div style="text-align: center;">
                    <strong>COMPAÑÍA DE SEGUROS Y REASEGUROS FORTALEZA S.A.</strong>
                </div>

            </div>
            
            @var $i++
        @endforeach
        @if($c < $copies)
            <page>
                <div style="page-break-before: always;">&nbsp;</div>
            </page>
        @endif
    @endfor    
</div>