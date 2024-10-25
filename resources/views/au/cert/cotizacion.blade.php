@if($tools == 1)
    @include('partials.tools_modal',array('type'=>$type,'idHeader'=>encode($header->id),'aux'=>$aux))
@endif    
<div style="width: 100%; height: auto; border: 0px solid #0081C2; padding: 10px;">
    <div style="width: 100%; font-weight: normal; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; ">
        <div style="width: 100%; border: 0px solid #FFFF00; text-align:center;">
            @include('partials.logos_modal',array('image1'=>$retailer->image,'images'=>$retailerProduct))
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                
                <tr><td colspan="3" style="width:100%;">&nbsp;</td></tr>
                <tr>
                    <td style="width:34%; text-align: left;">
                        SLIP DE COTIZACIÓN<br/>Cotizacion No {{ $header->quote_number }} 
                    </td>
                    <td style="width:32%;"></td>
                    <td style="width:34%; text-align:right;">
                        
                        Cotización válida hasta el: {{ $data['fecha_validacion'] }}
                    </td>
                </tr>
            </table><br/>
        </div><br>
        <div style="width: 100%; border: 0px solid #ffff00; text-align:center;">
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td colspan="3" style="width:100%; height: 20px; padding-bottom: 5px; text-align: left; font-weight: bold;">
                        Datos del Titular
                    </td>
                </tr>
                <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                    <td style="width:33%; text-align:center; font-weight:bold;">Apellido Paterno</td>
                    <td style="width:33%; text-align:center; font-weight:bold;">Apellido Materno</td>
                    <td style="width:34%; text-align:center; font-weight:bold;">Nombres</td>
                </tr>
                <tr>
                    <td style="width:33%; text-align:center;">{{ $header->client->last_name }}</td>
                    <td style="width:33%; text-align:center;">{{ $header->client->mother_last_name }}</td>
                    <td style="width:34%; text-align:center;">{{ $header->client->first_name }}</td>
                </tr>
                <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                    <td style="width:33%; text-align:center; font-weight:bold;">Documento de Identidad</td>
                    <td style="width:33%; text-align:center; font-weight:bold;">Genero</td>
                    <td style="width:34%; text-align:center; font-weight:bold;">Fecha de Nacimiento</td>
                </tr>
                <tr>
                    <td style="width:33%; text-align:center;">{{ $header->client->dni }}</td>
                    <td style="width:33%; text-align:center;">{{ $header->client->gender == 'M'?'Masculino':'Femenino' }}</td>
                    <td style="width:34%; text-align:center;">{{ date('Y-m-d',strtotime($header->client->birthdate)) }}</td>
                </tr>
                <tr style="font-weight:bold; background:#0075AA; color:#FFF;">
                    <td style="width:33%; text-align:center; font-weight:bold;">Telefono Domicilio</td>
                    <td style="width:33%; text-align:center; font-weight:bold;">Telefono Celular</td>
                    <td style="width:34%; text-align:center; font-weight:bold;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:33%; text-align:center;">{{ $header->client->phone_number_office }}</td>
                    <td style="width:33%; text-align:center;">{{ $header->client->phone_number_mobile }}</td>
                    <td style="width:34%; text-align:center;">&nbsp;</td>
                </tr>
            </table>
        </div><br>
        <div style="width: 100%; border: 0px solid #FFFF00; text-align:center;">
            <h2 style="width: auto;	height: auto; text-align: left; margin: 5px 0; padding: 0;
                font-weight: bold; font-size: 80%;">Datos de la solicitud de Crédito</h2>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr style="background:#E5E5E5;">
                    <td style="width:50%; text-align:right;"><b>Compañía de Seguros:</b></td>
                    <td style="width:50%; text-align: left;">{{ $companyProduct->company->name }}</td>
                </tr>
                <tr style="background:#D5D5D5;">
                    <td style="width:50%; text-align:right;"><b>Seguro a contratar:</b></td>
                    <td style="width:50%; text-align: left;">{{ $companyProduct->product->name }}</td>
                </tr>
                <tr style="background:#E5E5E5;">
                    <td style="width:50%; text-align:right;"><b>Periodo de contratacion:</b></td>
                    <td style="width:50%; text-align: left;">{{ $header->term }} {{ $header->type_term == 'Y'?'Años':'Meses' }} </td>
                </tr>
                @if($header->payment_method == 'AN')
                <tr style="background:#D5D5D5;">
                    <td style="width:50%; text-align:right;"><b>Prima Anual:</b></td>
                    <td style="width:50%; text-align: left;">{{ $header->total_premium/$time }}</td>
                </tr>
                @endif
                <tr style="background:#E5E5E5;">
                    <td style="width:50%; text-align:right;"><b>Prima total:</b></td>
                    <td style="width:50%; text-align: left;">{{ ($header->total_premium/$header->term)*$header->term }}</td>
                </tr>
                <tr style="background:#D5D5D5;">
                    <td style="width:50%; text-align:right;"><b>Inicio de vigencia:</b></td>
                    <td style="width:50%; text-align: left;">{{ date('Y-m-d',strtotime($header->validity_start)) }}</td>
                </tr>
                <tr style="background:#E5E5E5;">
                    <td style="width:50%; text-align:right;"><b>Fin de vigencia:</b></td>
                    <td style="width:50%; text-align: left;">{{ date('Y-m-d',strtotime($header->validity_end)) }}</td>
                </tr>
            </table>
        </div><br>
        <div style="width: 100%; border: 0px solid #FFFF00; text-align:center;">
            <h2 style="width: auto;	height: auto; text-align: left; margin: 5px 0; padding: 0;
                font-weight: bold; font-size: 80%;">Datos del Vehículo</h2>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td style="width:10%; text-align:center;"><b>Tipo de vehiculo</b></td>
                    <td style="width:10%; text-align:center;"><b>Marca</b></td>
                    <td style="width:10%; text-align:center;"><b>Modelo</b></td>
                    <td style="width:10%; text-align:center;"><b>Cero km.</b></td>
                    <td style="width:10%; text-align:center;"><b>Año</b></td>
                    <td style="width:10%; text-align:center;"><b>Placa</b></td>
                    <td style="width:10%; text-align:center;"><b>Categoria</b></td>
                    <td style="width:10%; text-align:center;"><b>Valor Asegurado</b></td>
                    <td style="width:10%; text-align:center;"><b>Tasa Total</b></td>
                    @if($header->payment_method == 'AN')
                    <td style="width:10%; text-align:center;"><b>Prima Anual</b></td>
                    @endif
                </tr>
                @foreach($header->details as $detail )
                <tr>
                    <td style="width:10%; text-align:center;">{{ $detail->vehicleType->vehicle }}</td>
                    <td style="width:10%; text-align:center;">{{ $detail->vehicleMake->make }}</td>
                    <td style="width:10%; text-align:center;">{{ $detail->vehicleModel->model }}</td>
                    <td style="width:10%; text-align:center;">{{ $detail->mileage == 1?'Si':'No' }}</td>
                    <td style="width:10%; text-align:center;">{{ $detail->year }}</td>
                    <td style="width:10%; text-align:center;">{{ $detail->license_plate }}</td>
                    @var $category = explode('C',$detail->category->category)
                    <td style="width:10%; text-align:center;">{{ $category[1] }}</td>
                    <td style="width:10%; text-align:center;">{{number_format($detail->insured_value,2,".",",")}}</td>
                    <td style="width:10%; text-align:center;">{{ $detail->rate }}</td>
                    @if($header->payment_method == 'AN')
                    <td style="width:10%; text-align:center;">{{ $detail->premium }}</td>
                    @endif
                </tr>
                @endforeach
            </table>
        </div><br>
        <div style="width: 100%; border: 0px solid #ffff00; text-align: center;">
            <h2 style="width: auto;	height: auto; text-align: left; margin: 5px 0; padding: 0;
                font-weight: bold; font-size: 80%;">Forma de Pago</h2>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 90%; height: auto; font-size: 80%; font-family: Arial;" align="center">
                <tr>
                    <td style="width:30%; text-align:center;"><b>Año</b></td>
                    <td style="width:30%; text-align:center;"><b>Fecha de Pago</b></td>
                    <td style="width:30%; text-align:center;"><b>Cuota</b></td>
                </tr>
                @foreach(json_decode($header->share) as $formaPago)
                <tr>
                    <td style="width:30%; text-align:center;">{{ $formaPago->number }}</td>
                    <td style="width:30%; text-align:center;">{{ date('Y-m-d',strtotime($formaPago->date)) }}</td>
                    <td style="width:30%; text-align:center;">{{ $formaPago->share }}</td>
                </tr>
                @endforeach
            </table>
        </div><br>

        <div style="width: 100%; border: 0px solid #FFFF00; text-align:justify; font-size: 80%;">
            <b>MATERIA ASEGURADA</b>
            Vehículos de propiedad de clientes de IDEPRO IFD. y que fueran objeto de garantía a favor del contratante, se deberán asegurar vehículos con una antigüedad máxima de 25 años.
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%; border: 0px solid #ffff00;">
                <tr>
                    <td style="width:2%; text-align: left;">A.</td>
                    <td style="width:98%; text-align: left;">
                        Vehículos Livianos: Particulares y Públicos, automóviles, vagonetas, minibuses, motocicleta, cuadratracks, y similares
                    </td>
                </tr>
                <tr>
                    <td style="width:2%; text-align: left;">B.</td>
                    <td style="width:98%; text-align: left;">
                        Vehículos Pesados y Semi Pesados: Particulares y Públicos, Microbuses, Flotas y Buses, Camiones, Tractocamiones, Volquetas, Chatas, vehículos de carga y/o Acoplados, y/o similares, que realizan viajes interdepartamentales e interprovinciales o de circulación en radio Urbano.
                    </td>
                </tr>
            </table>
            Accesorios hasta $us. 500,00 solamente para la categoría A, para vehículos particulares
            <br><br>
            <b>VALOR ASEGURADO:</b><br>
            Valor comercial según avalúo del vehículo y/o precio de mercado.<br>
            Para vehículos 0 kilómetros la factura de compra, proforma, o documento equivalente.<br><br>

            <b>COBERTURAS</b>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%;">
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Responsabilidad Civil Hasta $us 10.000.00</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Responsabilidad Consecuencial hasta $us 3.000,00</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Pérdida Total por Accidente al 100%</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Pérdida Total por robo al 100%</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Robo Parcial para vehículos livianos solo para la alternativa A al 80%, se excluye vehiculos pesados (Alternativa B) </td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;"> Daños propios, Huelgas, Riesgos Políticos, Conmoción Civil y Daño Malicioso incluyendo terrorismo, con Franquicia $us 50, y para vehículos de la alternativa B $us. 100.-</td>
                </tr>
            </table><br>
            <b>COBERTURA ADICIONAL</b><br>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%;">
                <tr>
                    <td style="width:100%;">Extraterritorialidad gratuita por toda la vigencia de la póliza, sin comunicación previa a la compañía y aplicable a todas las coberturas,  solamente para vehículos alternativa A</td>
                </tr>
            </table><br>

            <b>TASAS ANUALES</b><br>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%;">
                <tr>
                    <td style="width:100%; text-align:center;">VEHICULOS LIVIANOS: PARTICULARES Y PUBLICOS</td>
                </tr>
            </table><br>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td rowspan="2" style="width:50%; font-weight:bold; text-align:center; padding:3px; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; background:#D8D8D8;">
                        VALOR
                    </td>
                    <td colspan="5" style="width:50%; font-weight:bold; text-align:center; padding:3px; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        TASAS
                    </td>
                </tr>
                <tr>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 1
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 2
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 3
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 4
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 5
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; text-align:left; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;">
                        Menor o igual a $us. 100.000,00
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        1.75%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        3.40%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        4.99%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        6.51%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        7.88%
                    </td>
                </tr>
            </table><br><br>

            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%;">
                <tr>
                    <td style="width:100%; text-align:center;">VEHICULOS PESADOS: PARTICULARES Y PUBLICOS</td>
                </tr>
            </table><br>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td rowspan="2" style="width:50%; font-weight:bold; text-align:center; padding:3px; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000; background:#D8D8D8;">
                        VALOR
                    </td>
                    <td colspan="5" style="width:50%; font-weight:bold; text-align:center; padding:3px; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        TASAS
                    </td>
                </tr>
                <tr>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 1
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 2
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 3
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 4
                    </td>
                    <td style="width:10%; font-weight:bold; text-align:center; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; background:#D8D8D8;">
                        AÑO 5
                    </td>
                </tr>
                <tr>
                    <td style="width:50%; text-align:left; padding:3px; border-right: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid #000;">
                        Menor o igual a $us. 100.000,00
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        2.50%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        4.85%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        7.13%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        9.30%
                    </td>
                    <td style="width:10%; text-align:center; border-right: 1px solid #000; border-bottom: 1px solid #000;">
                        11.25%
                    </td>
                </tr>
            </table><br>

            <b>CLAUSULAS ADICIONALES</b><br>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%;">
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Adelanto del 50% en Caso de Siniestro </td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Ampliación de Aviso de Siniestro hasta 15 días</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Ausencia de Control solo para empresas</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Daños a Causa de Riesgo de la Naturaleza</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Elegibilidad de Talleres de Reparación</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Elegibilidad de Ajustadores</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Rehabilitación Automática de la Suma Asegurada</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Rescisión del Contrato a Prorrata</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Tránsito en Vías no Autorizadas</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Altas y Bajas (Inclusiones y Exclusiones) a prorrata</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Eliminación de la copia legalizada de Tránsito, para siniestros menores a $us 1.000,00.- excepto para casos de Responsabilidad Civil y Pérdida Total.</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Flete Aéreo</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Depreciación anual del 10% (En pólizas con vigencia mayor a un año)</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Subrogación, hasta finalizar el crédito</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De Errores y Omisiones (en la descripción de los datos de la materia asegurada y el llenado del formulario)</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De cobertura para eventos cuando el conductor del vehículo asegurado cuente con licencia de conducir, pero al momento de la ocurrencia del evento no la porte (un evento al año).</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De piezas y partes genuinas. Para vehículos importados</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De siniestros a consecuencia de Pérdida Total por accidente y/o robo a vehículos cuya antigüedad no exceda el primer año o los 10.000KM de recorrido, se deberá considerar como valor de indemnización, el valor de compra de un vehículo cero kilómetros, descontando la parte impositiva.</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">De ampliación de vigencia a prorrata hasta 90 días sin modificación de términos, condiciones, tasas y primas pactadas en el contrato inicial.</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Cobertura para bolsas de aire por daños a consecuencia de accidente de tránsito, robo y/o intento de robo sin ninguna limitación</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Asistencia en audiencias de transito</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Preparación y presentación de memoriales</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Asistencia a audiencias de Conciliación</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Gastos y costas judiciales (por acción civil)</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Presentación de fianzas judiciales (por acción civil)</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Se deja sin efecto la presentación del Test de Alcoholemia para accidentes ocurridos en el área rural o pueblos alejados de las ciudades principales. En su reemplazo la Aseguradora aceptara la presentación del informe de la autoridad competente de la localidad en la que haya ocurrido el siniestro o localidad más cercana.</td>
                </tr>
            </table><br>
            <b>CONDICIONES ESPECIALES</b><br>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size:80%;">
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">El valor asegurado corresponderá al valor comercial.</td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">
                        Para odo certificado emitido para vehículos cero kilómetros deberá adjuntarse únicamente la nota de entrega o factura de compra de la casa de venta.
                    </td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">La cobertura de robo total contratada, se extenderá a cubrir los daños y/o perdidas parciales ocurridas como consecuencia del robo total perpetrado, en la eventualidad de haberse logrado el recupero del vehículo dentro de los 45 días. </td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">El presente seguro se extiende a cubrir todos los daños y/o pérdidas que sufran los vehículos asegurados como consecuencia de cualquier servicio adicional que preste la compañía de seguros (instalaciones, auxilio mecánico, grúa, rastreo). </td>
                </tr>
                <tr>
                    <td style="width:2%;" valign="top">&bull;</td>
                    <td style="width:98%;">Aceptación del riesgo al que están expuestos los bienes, en función a las actividades que desarrolla el Contratante. </td>
                </tr>
            </table><br>
            <b>NOTAS ESPECIALES</b><br>
            EL ASEGURADO AUTORIZA A LA COMPAÑÍA DE SEGUROS A ENVIAR EL REPORTE A LA CENTRAL DE RIESGOS DEL MERCADO DE SEGUROS ACORDE A LAS NORMATIVAS REGLAMENTARIAS DE LA AUTORIDAD DE FISCALIZACIÓN Y CONTROL DE PENSIONES Y SEGUROS – APS.
            <br><br><br>
        </div>

        <div style="width: 100%; border: 0px solid #FFFF00; text-align:justify; font-size: 80%;">
            <table cellpadding="0" cellspacing="0" border="0" style="width: 60%; height: auto; font-size: 80%; font-family: Arial;">
                <tr>
                    <td style="width:30%;" align="center">{{ $header->client->first_name }} {{ $header->client->mother_last_name }} {{ $header->client->last_name }}</td>
                    <td style="width:30%;" align="center">{{ date('Y-m-d', strtotime($header->created_at)) }}</td>
                </tr>
                <tr>
                    <td style="width:30%;" align="center"><b>Titular 1</b></td>
                    <td style="width:30%;" align="center"><b>Fecha Actual</b></td>
                </tr>
            </table>
        </div>
    </div>
</div>