@var $version = false
@if($header->ad_certificate_id)
    @var $version = $header->versionCertificates()->where('ad_city_id',$header->user->city->id)->first()
@endif
<div style="width: 710px; font-weight: normal; font-size: 10px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
    @var $copies = 3;
    @for($c = ($copies == 1 ? 1 : $copies - 2); $c <= $copies; $c++)
        @foreach($header->details as $value)
        <div style="margin: 8mm 8mm 8mm 8mm;text-align: justify;">
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 11px; font-family: Arial, Helvetica, sans-serif; ">
                <tr>
                    <td style="width:70%;"><img src="{{ base_path('public/'.$header->retailerProduct->companyProduct->company->image) }}" height="55px"></td>
                    <td style="width:5%; text-align:right;"><br /><br /><strong>No. </strong></td>
                    <td style="width:25%; text-align:center;border-bottom:1px solid black;">
                        <br /><br />{{ $version->cert_number }}-{{ str_pad($header->issue_number, 4, 0, STR_PAD_LEFT) }}
                    </td>
                </tr>
            </table>
            <br />
            <div style="text-align: center">
                <strong>NOTA DE COBERTURA INDIVIDUAL A LA PÓLIZA DE SEGURO DE TODO RIESGO DE DAÑOS A LA PROPIEDAD</strong>
                <br />
                <br />
                <strong>POLIZA DE SEGURO TODO RIESGO DE DAÑOS A LA PROPIEDAD</strong>
            </div>
            <br />
            Asegurador: {{ $header->retailerProduct->companyProduct->company->name }}<br />
            Dirección: Av. Virgen de Cotoca No. 2080 – Santa Cruz<br />
            Tel: (591-3) 3487273<br />
            Fax: (591-3) 3497675<br />
            <div style="text-align: center;">
                <strong>Datos Generales del Asegurado</strong>
            </div>
            <br />
            Tomador: BANCO FORTALEZA S.A.<br />
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 11px; font-family: Arial, Helvetica, sans-serif; ">
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
                    <td style="width:20%; border-bottom:1px solid black;">{{ $header->date_issue }}</td>
                </tr>
            </table>
            <br />
            <strong>MATERIA DEL SEGURO:</strong> Inmuebles como garantías de los prestatarios del BANCO FORTALEZA S.A. y/o bajo custodia y/o control o por los cuales sea legalmente responsable,
            incluyendo obras civiles, instalaciones y vidrios, edificaciones, depósitos, galpones, muros perimetrales e instalaciones en general.<br />
            <br />
            <strong>COBERTURAS A VALOR TOTAL {{ $header->currency=='USD'?'$US':$header->currency }}.</strong><u>{{ number_format($value->insured_value, 2, ',', '.') }}</u> Todo riesgo de daños a la propiedad y/o pérdida física, incluyendo Asentamiento, Hundimiento de
            Terreno, Deslizamiento de Tierra, Terremoto, Temblor, Sismo y/o Erupción Volcánica.<br />
            <br />
            <strong>COBERTURAS A PRIMER RIESGO:</strong> En función del Valor Asegurado bajo esta sección.<br />
            <ul>
                <li>Terrorismo, hasta el 20% del valor asegurado</li>
                <li>Motines, Huelgas, Conmociones Civiles, Daño malicioso y Vandalismo, hasta el 20% del valor asegurado</li>
                <li>Sabotaje, hasta el 20% del valor asegurado</li>
            </ul>
            <br />
            
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 11px; font-family: Arial, Helvetica, sans-serif; ">
                <tr>
                    <td style="width:20%;"><strong>MATERIA DEL SEGURO: </strong></td>
                    <td style="width:80%; text-align:center;border-bottom:1px solid black;">
                        {{ config('base.property_types.'.$value->matter_insured) }}, {{$value->matter_description}}
                    </td>
                </tr>
            </table>
            <br />
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 11px; font-family: Arial, Helvetica, sans-serif; ">
                <tr>
                    <td style="width:50%; text-align: center;"><strong>UBICACIÓN DEL RIESGO</strong></td>
                    <td style="width:10%;"></td>
                    <td style="width:40%;"><strong>VALOR ASEGURADO</strong></td>
                </tr>
                <tr>
                    <td style="width:50%; border-bottom:1px solid black;">{{ $value->city }} {{ $value->address }}</td>
                    <td style="width:10%;"></td>
                    <td style="width:40%;border-bottom:1px solid black;">{{ $header->currency=='USD'?'$US':$header->currency }}. {{ number_format($value->insured_value, 2, ',', '.') }}</td>
                </tr>
            </table>
            <br />
            Prima Total {{ $header->currency=='USD'?'$US':$header->currency }}: {{ number_format($header->total_premium, 2, ',', '.') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Forma de pago: {{ $header->payment_method=='DA'?'Mensual':'Anual' }}
            <br />
            <strong>OBSERVACIONES</strong><br />
            La adhesión al presente seguro por parte del asegurado es voluntaria y tiene la posibilidad de reemplazar la(s) cobertura(s) exigida(s), con otra póliza de similares características.
            El presente certificado contiene un resumen de coberturas y exclusiones de la póliza de seguros que de manera completa se encuentra en poder del Tomador<br />
            <br /><br /><br /><br /><br /><br />
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 11px; font-family: Arial, Helvetica, sans-serif; ">
                <tr>
                    <td style="width:40%; border-bottom:1px solid black;">
                        
                    </td>
                    <td style="width:10%;"></td>
                    <td style="width:50%; border-bottom:1px solid black;text-align: center;">
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 11px; font-family: Arial, Helvetica, sans-serif; ">
                            <tr>
                                <td style="width:50%;text-align: center;">
                                    <img src="{{ base_path('public/images/firma_garantias/comercial-'.$header->prefix.'-'.$header->user->city->abbreviation.'.jpg') }}" width="150px"><br />
                                    Gerente Comercial {{ $header->user->city->name }}<br />
                                    FORTALEZA S.A.
                                </td>
                                <td style="width:50%;text-align: center;">
                                    <img src="{{ base_path('public/images/firma_garantias/gerente-'.$header->prefix.'-'.$header->user->city->abbreviation.'.jpg') }}" width="150px"><br />
                                    Gerente {{ $header->user->city->name }}<br />
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
            En consideración a la Solicitud de Seguro presentada en forma escrita por el Asegurado, y que constituye parte integrante e indivisible de la presente Póliza, al igual que cualquier otra
            declaración escrita del Asegurador y al pago de la prima correspondiente, la Póliza se extiende a cubrir, en los montos, términos y condiciones aquí estipulados, las pérdidas o daños
            materiales causados a los bienes asegurados, siempre que los daños sucedan de forma accidental, súbita e imprevista por cualquier causa, siempre y cuando las mismas no se encuentren
            expresamente mencionadas en las exclusiones de la presente póliza.<br />
            <strong>EXCLUSIONES:</strong><br />
            <ul type="1">
                <li>Joyas, piedras preciosas, metales preciosos, oro o plata en lingotes, pieles, curiosidades, libros raros, obras de arte, antigüedades, porcelana fina, mármol u otros objetos frágiles o
                quebradizos; y cualquier otro bien que tenga especial valor artístico, científico o histórico.</li>
                <li>Explosivos o sustancias inflamables.</li>
                <li>Cultivos en pie, bosques, ganado o animales de cualquier tipo, plantas, así como la quema de bosques y zarzales.</li>
                <li>Desplomes, asentamientos, hundimientos o expansiones normales o graduales de edificios o sus fundaciones, desplazamiento del terreno, desprendimiento de tierra o rocas.</li>
                <li>Rotura de maquinaria o daño eléctrico o avería de la maquinaria de la planta o equipo o equipos electrónicos.</li>
                <li>Las pérdidas o daños que por su propia explosión se causen a calderas o aparatos que trabajan normalmente a presión. Tampoco se asegura el daño al contenido de las calderas o
                aparatos bajo presión.</li>
                <li>Vicio propio, fermentación, uso y/o desgaste, herrumbre, incrustaciones, corrosión, oxidación, cavitación, evaporación, pérdida de peso, exposición a la luz, decoloración, combustión
                espontánea, cambio de textura y otros efectos graduales causados por el trabajo normal o por influencia permanente de sustancias químicas o de la atmósfera, así como las pérdidas
                resultantes de un defecto de fabricación, o por cualquier procedimiento de calefacción o desecación al cual hubieran sido sometidos los bienes asegurados.</li>
                <li>Daños causados por plagas, ratas, polillas o cualquier animal.</li>
                <li>Daños por chamuscado o cercanía al calor o fuente de calor, sin producir llama o cuando los objetos asegurados caigan aisladamente al fuego, a no ser que tales hechos ocurran
                con ocasión de un incendio propiamente dicho.</li>
                <li>La Compañía no responderá en concepto de gastos: a) destinados a rectificar en caso de material, defectuoso, ejecución o planificación deficientes; b) destinados a la conservación
                normal, reparación normal, mantenimiento; c) que se produzcan a causa de programación falsa o no autorizada, perforación, etiquetado o inserción, borrado inadvertido de
                informaciones o destrucción de soportes de datos y a causa de pérdidas de información sobre soportes de datos provocados por campos magnéticos.</li>
                <li>Robo, asalto y/o atraco.</li>
                <li>Desaparición misteriosa, falta inexplicable o falta de inventario, hurto.</li>
            </ul>
            <br />
            <strong>PROCEDIMIENTO EN CASO DE SINIESTRO:</strong><br />
            Al ocurrir un siniestro que cause pérdidas amparadas por la presente Póliza, el Asegurado tendrá la obligación de:<br />
            <ul>
                <li>Medidas de salvaguarda o recuperación: Ejecutar todos los actos que tiendan a evitar o disminuir el daño. Si no hay peligro en la demora, pedirá instrucciones a la Compañía y se
                atendrá a lo que ella le indique. El incumplimiento de esta obligación podrá afectar los derechos del Asegurado en los términos de Ley.</li>
                <li>Dar aviso del siniestro a la Compañía lo más pronto posible de tener conocimiento del siniestro, o como máximo dentro de diez (10) días hábiles de haberse suscitado el hecho de
                tránsito, salvo causa de fuerza mayor.</li>
                <li>Denunciar el hecho a las autoridades competentes más próximas y si por las características del evento se presume intervención de personas en forma culposa o dolosa, dar parte a
                las autoridades policiales correspondientes.</li>
                <li>El Asegurado conservará los bienes asegurados dañados y no podrá proceder a efectuar reparaciones en los bienes siniestrados sin autorización escrita de la Compañía, antes de
                que ésta o sus representantes inspeccionen los daños.</li>
            </ul>
            <br />
            <strong>IMPORTANTE:</strong><br />
            <ul>
                <li>Una vez cancelada la prima el Asegurado podrá descargar su factura de nuestra página web <a href="http://181.188.151.74:8070/factura/login" target="_blank">http://181.188.151.74:8070/factura/login</a>.</li>
                <li>Se otorgará cobertura mientras las primas se paguen de forma continua, el incumplimiento en el pago de la prima, dentro de los plazos fijados, suspende la vigencia de la póliza
                de seguro.</li>
                <li>El presente documento resume los términos y condiciones de la póliza principal suscrito por la Compañía de Seguros y Reaseguros Fortaleza S.A. y el Tomador del seguro, el
                mismo que respalda este certificado y prevalece sobre cualquier otro texto informativo.</li>
                <li>El Asegurado se adhiere voluntariamente al presente seguro, pudiendo reemplazar esta póliza en cualquier momento. Para la cancelación del presente seguro el Asegurado
                únicamente debe presentar una carta firmada dirigida al Tomador o la Compañía solicitando su baja del seguro, misma que surtirá efecto inmediato desde la fecha de recepción
                de tal nota.</li>
                <li>Las partes reconocen que las condiciones del Seguro y la póliza, pueden cambiar y que en todos los casos el Asegurado debe informarse sobre los cambios o modificaciones
                en la cobertura de los seguros contratados por el Tomador</li>
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