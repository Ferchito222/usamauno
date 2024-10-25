<div style="width: 775px; height: auto; border: 0px solid #0081C2; padding: 5px;">
    <div style="width: 770px; font-weight: normal; font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
        @var $k=1
        @foreach($query_quest_cl as $data_cl)
            @var $fecha = date('d/m/Y', strtotime($data_cl->birthdate));
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:right;">
                <div style="font-size: 70%; width: auto; height: auto; font-weight: bold; text-align: center;">
                    DECLARACIÓN JURADA DE SALUD <br>
                    SOLICITUD DE SEGURO MASIVO VIDA INDIVIDUAL DE CORTO PLAZO
                </div>
                <div style="font-size: 65%; width: auto; height: auto; margin-top: 3px; text-align: center;">
                    Formato aprobado por la Autoridad de Fiscalización y Control de Pensiones y
                    Seguros – APS  mediante Resolución Administrativa APS/DS/No.687-2013 <br>
                    Código 206-934215-2013 07 035 3001
                </div>
                <div style="font-size: 65%; width: auto; height: auto; font-weight: bold; margin-top: 3px; text-align: center;">
                    El interesado solicita  a Nacional Vida Seguros de Personas S.A, un seguro
                    de vida, basado en las declaraciones que siguen a continuación, las mismas
                    que formaran parte integrante e indivisible de la póliza:
                </div>
                <div style="font-size: 65%; width: auto; height: auto; font-weight: bold; margin-top: 3px; text-align: right;">
                    {{'V - '.$data_cl->issue_number}}
                </div>

            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <div style="width: auto;	height: auto; text-align: left; margin: 2px 0; padding: 0; font-weight: bold; font-size: 65%;">
                    I.	DATOS PERSONALES DEL SOLICITANTE:
                </div>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 14%; text-align: left;">Nombre del Tomador: </td>
                        <td style="width: 86%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->first_name.' '.$data_cl->last_name.' '.$data_cl->mother_last_name}}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 15%; text-align: left;">Nombre del Asegurado: </td>
                        <td style="width: 85%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->first_name.' '.$data_cl->last_name.' '.$data_cl->mother_last_name}}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 14%; text-align: left;">Lugar de Nacimiento:</td>
                        <td style="width: 32%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->birth_place}}
                        </td>
                        <td style="width: 14%; text-align: left;">Fecha de Nacimiento:</td>
                        <td style="width: 37%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$fecha}}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 18%; text-align: left;">No Documento de Identidad:</td>
                        <td style="width: 13%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->dni}}
                        </td>
                        <td style="width: 8%; text-align: left;">Expedido en:</td>
                        <td style="width: 19%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->extension}}
                        </td>
                        <td style="width: 4%; text-align: left;">Edad:</td>
                        <td style="width: 9%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->age}}
                        </td>
                        <td style="width: 4%; text-align: left;">Peso:</td>
                        <td style="width: 9%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->weight}}
                        </td>
                        <td style="width: 7%; text-align: left;">Estatura:</td>
                        <td style="width: 9%; border-bottom: 1px solid #080808;">
                            {{$data_cl->height}}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 12%; text-align: left;">Dirección Domicilio:</td>
                        <td style="width: 38%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->home_address}}
                        </td>
                        <td style="width: 12%; text-align: left;">Correo Electronico</td>
                        <td style="width: 38%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->email}}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 12%; text-align: left;">Teléfono Domicilio:</td>
                        <td style="width: 28%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->phone_number_home}}
                        </td>
                        <td style="width: 11%; text-align: left;">Teléfono Oficina:</td>
                        <td style="width: 19%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->phone_number_office}}
                        </td>
                        <td style="width: 11%; text-align: left;">Teléfono Celular:</td>
                        <td style="width: 19%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->phone_number_mobile}}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 12%; text-align: left;">Actividad Laboral</td>
                        <td style="width: 88%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->occupation}}
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 12%; text-align: left;">Lugar de Trabajo</td>
                        <td style="width: 88%; border-bottom: 1px solid #080808; text-align: left;">
                            {{$data_cl->business_address}}
                        </td>
                    </tr>
                </table>
            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <div style="width: auto;	height: auto; text-align: left; margin: 2px 0; padding: 0;
                        font-weight: bold; font-size: 70%;">II. ELECCION DEL PLAN</div>
                <div style="font-size: 75%; text-align: left; margin: 2px 0;">1. Planes del Seguro</div>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 75%; background: #000; color: #fff; text-align: center;
                            height: 15px; border: 1px solid #000;" colspan="2">
                            Expresado en Bolivianos
                        </td>
                        <td style="width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 55%; background: #353535; color: #fff; height: 15px;
                            border: 1px solid #000; text-align: left;">
                            Coberturas
                        </td>
                        <td style="width: 20%; background: #353535; color: #fff;
                            border: 1px solid #000; text-align: left;">
                            Rangos de Capitales
                        </td>
                        <td style="width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 55%; background: #000; color: #fff; border: 1px solid #080808;
                            text-align: left;">
                            Muerte por cualquier causa
                        </td>
                        <td style="width: 20%; border: 1px solid #080808;">
                            Hasta Bs. {{number_format(14000, 0, '.', ',')}}&nbsp;
                        </td>
                        <td style="width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 55%; background: #000; color: #fff; border: 1px solid #080808;
                            text-align: left;">
                            Pago Anticipado del capital asegurado en caso de invalidez Total y Permanente
                        </td>
                        <td style="width: 20%; border: 1px solid #080808;" >
                            Hasta Bs. {{number_format(14000, 0, '.', ',')}}&nbsp;
                        </td>
                        <td style="width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 55%; background: #000; color: #fff; border: 1px solid #080808;
                            text-align: left;">
                            Sepelio
                        </td>
                        <td style="width: 20%; border: 1px solid #080808;" >
                            Hasta Bs. {{number_format(2100, 0, '.', ',')}}&nbsp;
                        </td>
                        <td style="width: 15%;"></td>
                    </tr>
                </table>
            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <div style="width: auto;	height: auto; text-align: left; margin: 2px 0; padding: 0;
                        font-weight: bold; font-size: 70%;">III. BENEFICIARIO</div>
                <div style="font-size: 75%; text-align: left; margin: 2px 0;">Beneficiarios:</div>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 10%; background: #000; color: #fff; text-align: center;
                            height: 10px; padding-top: 5px;" >

                        </td>
                        <td style="width: 35%; background: #000; color: #fff; text-align: center;
                            padding-top: 5px; border: 1px solid #000;" >
                            Nombre Completo
                        </td>
                        <td style="width: 10%; background: #000; color: #fff; text-align: center;
                            padding-top: 5px; border: 1px solid #000;" >
                            Parentesco
                        </td>
                        <td style="width: 15%; background: #000; color: #fff; text-align: center;
                            padding-top: 5px; border: 1px solid #000;" >
                            Carnet de Identidad
                        </td>
                        <td style="width: 10%; background: #000; color: #fff; text-align: center;
                            padding-top: 5px; border: 1px solid #000;" >
                            Proporcion (%)
                        </td>
                        <td style="width: 10%;"></td>
                    </tr>
                    @var $j=1

                    @foreach($arr_benefi_subp[$k] as $data_benef)

                        <tr>
                            <td style="width: 10%;"></td>
                            <td style="width: 10%; background: #000; color: #fff; text-align: center;
                                height: 10px; padding-top: 2px; border: 1px solid #000;" >

                            </td>
                            <td style="width: 35%; border: 1px solid #000; text-align: center;
                                padding-top: 2px;" >
                                {{$data_benef['first_name'].' '.$data_benef['last_name'].' '.$data_benef['mother_last_name']}}
                            </td>
                            <td style="width: 10%; border: 1px solid #000; text-align: center;
                                padding-top: 2px;" >
                                {{$data_benef['relationship']}}
                            </td>
                            <td style="width: 15%; border: 1px solid #000; text-align: center;
                                padding-top: 2px;" >
                                {{$data_benef['dni'].' '.$data_benef['extension']}}
                            </td>
                            <td style="width: 10%; border: 1px solid #000; text-align: center;
                                padding-top: 2px;" >
                                {{$data_benef['participation']}}
                            </td>
                            <td style="width: 10%;"></td>
                        </tr>
                        @var $j = $j +1
                    @endforeach
                </table>
            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <div style="width: auto;	height: auto; text-align: left; margin: 2px 0; padding: 0; font-weight: bold; font-size: 65%;">
                    IV. CUESTIONARIO DE SALUD: (Marque con una X si corresponde)
                </div>
                <div style="font-size: 70%; text-align: left; margin: 2px 0;">
                    ¿Usted padece de alguna de las siguientes enfermedades?
                </div>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial;">
                    @foreach(json_decode($data_cl->response) as $key => $value)
                        <tr>
                            <td style="width: 2%; text-align: left;">{{$key}}</td>
                            <td style="width: 84%; text-align: left;">
                                {{$value->question}}
                            </td>
                            <td style="width: 2.5%;">SI</td>
                            <td style="width: 3%; text-align: right;">
                                <div style="width:14px; height:10px; border:1px solid #333; text-align:center; vertical-align: middle;">
                                    {{$value->response==1?'x':''}}
                                </div>
                            </td>
                            <td style="width: 3%;"></td>
                            <td style="width: 2.5%;">NO</td>
                            <td style="width: 3%; text-align: right;">
                                <div style="width:14px; height:10px; border:1px solid #333; text-align:center; vertical-align: middle;">
                                    {{$value->response==0?'x':''}}
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </table><br>
            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <div style="width: auto;	height: auto; text-align: left; margin: 2px 0; padding: 0;
                        font-weight: bold; font-size: 70%;">V. FORMA DE PAGO </div>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 10%; "></td>
                        <td style="width: 20%; height: 5px; padding: 5px; text-align: left;">
                            Pago al Contado
                        </td>
                        <td style="width: 5%; padding: 2px 0; " >
                            <div class="input-check">&nbsp;</div>
                        </td>
                        <td style="width: 15%;"></td>
                        <td style="width: 25%; padding: 5px; text-align: left;">
                            Debito Automatico
                        </td>
                        <td style="width: 5%; padding: 2px 0;" >
                            <div class="input-check">&nbsp;</div>
                        </td>
                        <td style="width: 20%; "></td>
                    </tr>
                </table>
            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <div style="width: auto;	height: auto; text-align: left; margin: 2px 0; padding: 0;
                        font-weight: bold; font-size: 70%;">VI. PERIODICIDAD</div>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 10%; "></td>
                        <td style="width: 20%; height: 5px; padding: 5px; text-align: left;">
                            Pago Anual
                        </td>
                        <td style="width: 5%; padding: 2px 0; " >
                            <div class="input-check">&nbsp;</div>
                        </td>
                        <td style="width: 15%;"></td>
                        <td style="width: 25%; padding: 5px; text-align: left;">
                            Pago Mensual
                        </td>
                        <td style="width: 5%; padding: 2px 0;" >
                            <div class="input-check">&nbsp;</div>
                        </td>
                        <td style="width: 20%; "></td>
                    </tr>
                </table>
            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <div style="font-size: 75%; text-align: left; margin: 2px 0;">
                    Debito en Cuenta de la Entidad Financiera
                </div>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 20%; background: #000; color: #fff;
                            height: 10px; padding-top: 5px;" >
                            Número de Cuenta 1
                        </td>
                        <td style="width: 40%; padding-top: 5px; padding-left: 5px;
                            border-bottom: 1px solid #000;" >
                            1071022974
                        </td>
                        <td style="width: 30%; border-left: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 20%; background: #000; color: #fff;
                            height: 10px; padding-top: 5px;" >
                            Número de Cuenta 2
                        </td>
                        <td style="width: 40%; padding-top: 5px; padding-left: 5px;
                            border-bottom: 1px solid #000;" >

                        </td>
                        <td style="width: 30%; border-left: 1px solid #000;"></td>
                    </tr>
                    <tr>
                        <td style="width: 10%;"></td>
                        <td style="width: 20%; background: #000; color: #fff;
                            height: 10px; padding-top: 5px;" >
                            Tarjeta de Crédito
                        </td>
                        <td style="width: 40%; padding-top: 5px; padding-left: 5px;
                            border-bottom: 1px solid #000;" >

                        </td>
                        <td style="width: 30%; border-left: 1px solid #000;"></td>
                    </tr>
                </table>
            </div>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:justify; font-size: 65%; margin-top: 4px;">
                Declaro haber contestado con total veracidad y máxima buena fe a todas las preguntas del presente
                cuestionario y no haber omitido u ocultado hechos y/o circunstancias que hubieran podido influir en la
                celebración del contrato de seguro, las mismas que son completas y verídicas.
                <br>
                Las declaraciones de salud que hacen anulable el Contrato de Seguros y por las que el asegurado pierde
                su derecho a indemnización, se enmarcan en los artículos 992: OBLIGACION DE DECLARAR; 993: RETICENCIA
                O INEXACTTUD; 994: AUSENCIA DE DOLO; 999: DOLO O MALA FE; 1038: PERDIDA AL DERECHO DE LA INDEMNIZACION;
                1138: IMPUGNACION DEL CONTRATO; 1140: ERROR EN LA EDAD DEL ASEGURADO, del Código de Comercio.
                <br>
                Por la presente acepto que esta solicitud no es un contrato de seguro y que este solo existirá si se
                emite y entrega el Certificado de Cobertura de acuerdo con esta solicitud y los reglamentos de Seguros
                Masivos autorizados por la APS.
                <br>
                Autorizo a Médicos, Clínicas e Institutos de Salud para suministrar a Nacional Vida Seguro de Personas
                S.A., todos los datos que requiera sobre mi estado de salud antes o después de mi fallecimiento.
            </div>
            <br><br>
            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 5%; ">
                            Fecha:
                        </td>
                        <td style="width: 35%; height: 5px; border-bottom: 1px solid #080808;" >&nbsp;

                        </td>
                        <td style="width: 5%;" >
                            Firma:
                        </td>
                        <td style="width: 35%; border-bottom: 1px solid #080808;">&nbsp;

                        </td>
                        <td style="width: 20%;">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 5%;">
                        </td>
                        <td style="width: 35%; height: 5px;" >
                        </td>
                        <td style="width: 5%;">
                        </td>
                        <td style="width: 35%; text-align: center;">
                            SOLICITANTE
                        </td>
                        <td style="width: 20%;">
                        </td>
                    </tr>
                </table>
                <br><br>
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-size: 65%; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width: 5%; ">
                        </td>
                        <td style="width: 35%; height: 5px; " >
                        </td>
                        <td style="width: 5%;" >
                            Firma:
                        </td>
                        <td style="width: 35%; border-bottom: 1px solid #080808;">&nbsp;

                        </td>
                        <td style="width: 20%; " >
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 5%; ">
                        </td>
                        <td style="width: 35%; height: 5px;" >
                        </td>
                        <td style="width: 5%;" >
                        </td>
                        <td style="width: 35%; text-align: center;">
                            BANCO
                        </td>
                        <td style="width: 20%; " >
                        </td>
                    </tr>
                </table>
            </div>

            <page><div style="page-break-before: always;">&nbsp;</div></page>

            <div style="width: 770px; border: 0px solid #FFFF00; text-align:center;">
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; height: auto; font-family: Arial; margin-bottom: 5px;">
                    <tr>
                        <td style="width:50%; text-align: justify; padding:5px; border:0px solid #333;" valign="top">
                            <div style="width: auto; height: auto; font-weight: bold; text-align: center; font-size: 60%;">
                                DECLARACIÓN JURADA DE SALUD <br>
                                SOLICITUD DE SEGURO MASIVO VIDA INDIVIDUAL DE CORTO PLAZO
                            </div>
                            <div style="width: auto; height: auto; margin-top: 3px; text-align: center; font-size: 60%;">
                                Formato aprobado por la Autoridad de Fiscalización y Control de
                                Pensiones y Seguros APS  mediante Resolución Administrativa
                                <br>
                                APS/DSNo.687-2013 Código 206-934215-2013 07  035 400
                                <br>
                                POLIZA DE  SEGURO MASIVO VIDA DE CORTO PLAZO N° 123456
                            </div>
                            <p style="text-align: justify; font-size: 60%;">
                                NACIONAL VIDA Seguros de Personas S.A., (denominada en adelante "LA COMPAÑÍA "),
                                por el presente CERTIFICADO INDIVIDUAL DE SEGURO hace constar que la persona
                                nominada en la declaración jurada de salud / formulario de seguro, (denominado
                                en adelante "EL ASEGURADO"), está protegido por la Póliza de Seguro de Vida
                                Masiva arriba mencionada, de acuerdo a las  siguientes Condiciones Particulares:
                            </p>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                1. DATOS DEL ASEGURADO ASEGURADO
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 27%; text-align: left;">
                                        Nombre Completo:
                                    </td>
                                    <td style="width: 73%; border-bottom: 1px solid #080808; text-align: left;">
                                        ROSA KARINA YERBALES CORNEJO
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 22%; text-align: left;">
                                        Cedula de Identidad:
                                    </td>
                                    <td style="width: 73%; border-bottom: 1px solid #080808; text-align: left;">
                                        6393199 SC
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 22%; text-align: left;">
                                        Dirección Domicilio:
                                    </td>
                                    <td style="width: 73%; border-bottom: 1px solid #080808; text-align: left;">
                                        BARRIO CARLOS LABORDE ZONA SUR
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 22%; text-align: left;">
                                        Fecha de Nacimiento:
                                    </td>
                                    <td style="width: 73%; border-bottom: 1px solid #080808; text-align: left;">
                                        1987-03-22
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                2. COBERTURAS Y CAPITALES ASEGURADOS:
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                <tr>
                                    <td style="width: 5%; font-size: 60%;">
                                        a.
                                    </td>
                                    <td style="width: 95%; text-align: justify; font-size: 60%;">
                                        <label style="font-weight: bold;">Muerte por cualquier causa:</label> La compañía asume
                                        la muerte por cualquier causa de EL ASEGURADO, siempre y cuando la causa
                                        no se encuentre excluida en el punto  4 del presente certificado.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-size: 60%;">
                                        b.
                                    </td>
                                    <td style="width: 95%; text-align: justify; font-size: 60%;">
                                        <label style="font-weight: bold;">Pago anticipado del Capital Asegurado en caso de
                                            Invalidez Total y Permanente:</label>
                                        Cobertura aplicable cuando se presenta la Incapacidad Total y Permanente en
                                        forma irreversible y por lo menos en un 60% de incapacidad, por Accidente o
                                        por Enfermedad
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-size: 60%;">
                                        c.
                                    </td>
                                    <td style="width: 95%; text-align: justify; font-size: 60%;">
                                        <label style="font-weight: bold;">Sepelio:</label> El beneficio de sepelio no considera
                                        exclusiones para EL ASEGURADO y se paga al ocurrir la muerte por cualquier
                                        causa.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-size: 60%;" valign="top">
                                        d.
                                    </td>
                                    <td style="width: 95%; text-align: justify;">
                                        <label style="font-weight: bold; font-size: 60%;">Capitales Asegurados:</label>
                                        <br><br>
                                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                            <tr>
                                                <td style="width: 100%; background: #000; color: #fff;
                                                    text-align: center; border: 1px solid #000;" colspan="2">
                                                    Expresado en Bolivianos
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%; background: #353535; color: #fff;
                                                    border: 1px solid #000;">
                                                    Coberturas
                                                </td>
                                                <td style="width: 30%; background: #353535; color: #fff;
                                                    border: 1px solid #000;">
                                                    Rango de Capitales
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%; border: 1px solid #000;
                                                    background: #000; color: #fff;">
                                                    Muerte por cualquier causa
                                                </td>
                                                <td style="width: 30%; padding-left: 5px; border: 1px solid #000;">
                                                    Hasta Bs. {{number_format(14000, 0, '.', ',')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%; border: 1px solid #000;
                                                    background: #000; color: #fff;">
                                                    Pago Anticipado del capital asegurado en caso de invalidez Total
                                                    y Permanente
                                                </td>
                                                <td style="width: 30%; padding-left: 5px; border: 1px solid #000;">
                                                    Hasta Bs. {{number_format(14000, 0, '.', ',')}}&nbsp;
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 70%; border: 1px solid #000;
                                                    background: #000; color: #fff;">
                                                    Sepelio
                                                </td>
                                                <td style="width: 30%; padding-left: 5px; border: 1px solid #000;">
                                                    Hasta Bs. {{number_format(2100, 0, '.', ',')}}&nbsp;
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                            <tr>
                                                <td style="width: 20%;">
                                                    Límites de edad:
                                                </td>
                                                <td style="width: 20%;">
                                                    De Ingreso:
                                                </td>
                                                <td style="width: 60%; text-align: justify;">
                                                    De Ingreso:	Mayores de 14 años y  hasta los 70 años por
                                                    muerte por cualquier causa. Mayores de 14 años y hasta
                                                    los 64 años para Pago anticipado del Capital Asegurado
                                                    en caso de Invalidez Total y Permanente
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 20%;">

                                                </td>
                                                <td style="width: 20%;">
                                                    De permanencia:
                                                </td>
                                                <td style="width: 60%; text-align: justify;">
                                                    De permanencia: Hasta los 75 años en caso de muerte por
                                                    cualquier causa Hasta los 65 años de Pago anticipado del
                                                    Capital Asegurado en caso de Invalidez Total y Permanente.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                3. PUNTO DE VENTA:
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 35%; text-align: left;">
                                        Nombre de la Razón Social:
                                    </td>
                                    <td style="width: 65%; text-align: left">
                                        Banco Económico S.A.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 35%; text-align: left;">
                                        Dirección:
                                    </td>
                                    <td style="width: 65%; text-align: left;">
                                        Santa Cruz, SANTA CRUZ
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                4. EXCLUSIONES:
                                    <span style="font-weight: normal;">
                                        Este seguro de  vida no será aplicable en ninguna de
                                        las siguientes circunstancias:
                                    </span><br>
                                Para Cobertura de Muerte por Cualquier Causa:
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        Participación de EL ASEGURADO en actos de guerra, declarada o no, sedición,
                                        rebelión, asonada, conspiración, motín, tumulto, o cualquier acto que tenga
                                        relación con ellos, salvo comprobación de que EL ASEGURADO no haya participado,
                                        o formado parte activa de dichos actos de manera directa.
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        Participación de EL ASEGURADO como conductor o acompañante en competencias de
                                        velocidad o resistencia, de automóviles motocicletas, lanchas o en cualquier
                                        clase de vehículos a motor.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        Suicidio durante el primer año de haber estado asegurado ininterrumpidamente.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        Fisión o Fusión nuclear o contaminación radioactiva.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">La Cobertura de Sepelio no tiene exclusiones.</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Para la cobertura Pago anticipado del Capital Asegurado
                                        en caso de Invalidez Total y Permanente:</td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        La utilización por EL ASEGURADO de medios de transporte aéreo no comercial,
                                        salvo en calidad de pasajero de líneas aéreas debidamente autorizadas para
                                        el transporte público.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        Que EL ASEGURADO se encuentre en estado de ebriedad o bajo los efectos de
                                        Alcohol, drogas o alucinógenos.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        Falsas declaraciones, omisión o reticencia del Asegurado que puedan influir
                                        en la comprobación de su estado de invalidez.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; text-align: center; font-weight: normal;" valign="top">*</td>
                                    <td style="width: 95%;">
                                        Intento de suicidio cualquiera sea la época en que ocurra o por medidas o
                                        lesiones inferidas AL ASEGURADO por sí mismo o por terceros con su
                                        consentimiento.
                                    </td>
                                </tr>
                            </table>
                        <td>
                        <td style="width:50%; text-align: justify; padding:5px; border:0px solid #333;" valign="top">
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                5. COSTO DE LA COBERTURA:
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 27%; background: #000; color: #FFF; padding: 3px; border: 1px solid #000;">
                                        Prima Neta
                                    </td>
                                    <td style="width: 28%; border: 1px solid #000; padding: 2px;">
                                        237.15
                                    </td>
                                    <td style="width: 45%;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 27%; background: #000; color: #FFF; padding: 2px; border: 1px solid #000;">
                                        Impuestos
                                    </td>
                                    <td style="width: 28%; border: 1px solid #000; padding: 2px;">
                                        2.7
                                    </td>
                                    <td style="width: 45%;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 27%; background: #000; color: #FFF; padding: 2px; border: 1px solid #000;">
                                        Comision Corretaje
                                    </td>
                                    <td style="width: 28%; border: 1px solid #000; padding: 2px;">
                                        12.15
                                    </td>
                                    <td style="width: 45%;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 27%; background: #000; color: #FFF; padding: 2px; border: 1px solid #000;">
                                        Prima Comercial
                                    </td>
                                    <td style="width: 28%; border: 1px solid #000; padding: 2px;">
                                        252.00
                                    </td>
                                    <td style="width: 45%;"></td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                6. BENEFICIARIOS:
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 15%; text-align: center; background: #000;
                                        color: #FFF; height: 10px;">
                                    </td>
                                    <td style="width: 35%; background: #000; color: #FFF;
                                        height: 10px; text-align: center; border: 1px solid #000;">
                                        Nombre Completo
                                    </td>
                                    <td style="width: 15%; background: #000; color: #FFF;
                                        text-align: center; border: 1px solid #000;">
                                        Parentesco
                                    </td>
                                    <td style="width: 20%; background: #000; color: #FFF;
                                        text-align: center; border: 1px solid #000;">
                                        C.I.
                                    </td>
                                    <td style="width: 15%; background: #000; color: #FFF;
                                        text-align: center; border: 1px solid #000;">
                                        Proporción
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%; text-align: center; background: #000;
                                        color: #FFF; border: 1px solid #000; height: 10px;">
                                        Beneficiario 1
                                    </td>
                                    <td style="width: 35%; text-align: center; border: 1px solid #000;">
                                        JORGE DANIEL CHOQUEHUANCA YERBALES
                                    </td>
                                    <td style="width: 15%; text-align: center; border: 1px solid #000;">
                                        HIJO
                                    </td>
                                    <td style="width: 20%; text-align: center; border: 1px solid #000;">
                                        13431238-SC
                                    </td>
                                    <td style="width: 15%; text-align: center; border: 1px solid #000;">
                                        50.00
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%; text-align: center; background: #000;
                                        color: #FFF; border: 1px solid #000; height: 10px;">
                                        Beneficiario 2
                                    </td>
                                    <td style="width: 35%; text-align: center; border: 1px solid #000;">
                                        JULIANA CHOQUEHUANCA YERBALES
                                    </td>
                                    <td style="width: 15%; text-align: center; border: 1px solid #000;">
                                        HIJA
                                    </td>
                                    <td style="width: 20%; text-align: center; border: 1px solid #000;">
                                        13431248-SC
                                    </td>
                                    <td style="width: 15%; text-align: center; border: 1px solid #000;">
                                        50.00
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 85%; background: #000; color: #FFF;" colspan="4"></td>
                                    <td style="width: 15%; background: #000; color: #FFF;
                                        text-align: center; border: 1px solid #000;">100%
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                7. PROCEDIMIENTO A SEGUIR EN CASO DE SINIESTRO:
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 100%;" colspan="2">
                                        El Asegurado o Beneficiario, tan pronto y a más tardar dentro de los 30 días
                                        de tener conocimiento del siniestro, debe comunicar tal hecho a la aseguradora,
                                        salvo fuerza mayor o impedimiento jurídico.
                                        <br>
                                        Para reclamar el pago de cualquier indemnización con cargo
                                        a esta póliza, EL ASEGURADO O BENEFICIARIO deberá remitir a
                                        LA COMPAÑÍA su solicitud junto con los documentos a presentar
                                        en caso de fallecimiento o invalidez. LA COMPAÑÍA podrá, a sus
                                        expensas, recabar informes o pruebas complementarias.
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                DOCUMENTOS  A PRESENTAR  EN CASO DE  MUERTE POR CUALQUIER CAUSA:
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Certificado Médico de defunción.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Certificado de la Autoridad competente (si corresponde).
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Documento de identidad (carnet de identidad o certificado
                                        de nacimiento) del asegurado.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Documento de identidad (Carnet de identidad o Certificado
                                        de nacimiento) del beneficiario.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Declaratoria de Herederos si no existieran Beneficiarios
                                        nominados en la Póliza.
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                DOCUMENTOS  A PRESENTAR  EN CASO DE  INVALIDEZ TOTAL PERMANENTE
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%; text-align: justify;">
                                        Declaración médica de invalidez, emitida por un médico autorizado por la APS.
                                    </td>
                                </tr>
                            </table>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                DOCUMENTOS  A PRESENTAR  EN CASO DE  SEPELIO
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Certificado de defunción.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Documento de identidad (carnet de identidad o certificado
                                        de nacimiento) del asegurado.

                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Documento de identidad (Carnet de identidad o Certificado de
                                        nacimiento) del beneficiario.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 5%; font-weight: bold; text-align: center;">*</td>
                                    <td style="width: 95%;">
                                        Declaratoria de Herederos si no existieran Beneficiarios
                                        nominados en la Póliza.
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                8. COMPAÑÍA ASEGURADORA
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                <tr>
                                    <td style="width: 100%;">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                            <tr>
                                                <td style="width: 14%; text-align: left;">
                                                    Razón Social:
                                                </td>
                                                <td style="width: 86%; border-bottom: 1px solid #000; text-align: left;"
                                                    colspan="5">
                                                    Nacional Vida Seguros de Personas S.A.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:14%; text-align: left;">Dirección:</td>
                                                <td style="width:36%; border-bottom: 1px solid #000;">
                                                    Av. Monseñor Rivero  N 223
                                                </td>
                                                <td style="width:7%;">Teléfono:</td>
                                                <td style="width:18%; border-bottom: 1px solid #000;">
                                                    3716262
                                                </td>
                                                <td style="width:7%;">Fax:</td>
                                                <td style="width:18%; border-bottom: 1px solid #000;">
                                                    3337969
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br>
                            <div style="text-align: left; margin: 2px 0; font-weight: bold; font-size: 60%;">
                                9. CORREDOR DE SEGUROS
                            </div>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;">
                                <tr>
                                    <td style="width: 100%;">
                                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                            <tr>
                                                <td style="width: 14%; text-align: left;">
                                                    Razón Social:
                                                </td>
                                                <td style="width: 86%; border-bottom: 1px solid #000; text-align: left;"
                                                    colspan="5">
                                                    Sudamericana S.R.L.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:14%;">Dirección:</td>
                                                <td style="width:36%; border-bottom: 1px solid #000;">
                                                    Equipetrol Calle Nº 8 Este Nº 25
                                                </td>
                                                <td style="width:7%;">Teléfono:</td>
                                                <td style="width:18%; border-bottom: 1px solid #000;">
                                                    3416055
                                                </td>
                                                <td style="width:7%;">Fax:</td>
                                                <td style="width:18%; border-bottom: 1px solid #000;">
                                                    3416056
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br><br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 13%;"></td>
                                    <td style="width: 20%; border-bottom: 1px solid #000;
                                            text-align: center;">
                                        Santa Cruz
                                    </td>
                                    <td style="width: 4%; text-align: center;"> , </td>
                                    <td style="width: 10%; border-bottom: 1px solid #000;
                                            text-align: center;">
                                        30
                                    </td>
                                    <td style="width: 5%; text-align: center;"> de </td>
                                    <td style="width: 20%; border-bottom: 1px solid #000;
                                            text-align: center;">
                                        Noviembre
                                    </td>
                                    <td style="width: 5%; text-align: center;"> de </td>
                                    <td style="width: 10%; border-bottom: 1px solid #000;
                                            text-align: center;">
                                        2015
                                    </td>
                                    <td style="width: 13%;"></td>
                                </tr>
                            </table>
                            <br>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; font-size: 60%;">
                                <tr>
                                    <td style="width: 5%;"></td>
                                    <td style="width: 90%; text-align: center; padding-bottom: 20px;"
                                        colspan="3">
                                        NACIONAL VIDA SEGURO DE  PERSONAS S.A.
                                        <br>
                                        FIRMAS AUTORIZADAS
                                    </td>
                                    <td style="width: 5%;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 5%;"></td>
                                    <td style="width: 35%; border-bottom: 1px solid #000; text-align: center;">

                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td style="width: 45%; border-bottom: 1px solid #000; text-align: center;">

                                    </td>
                                    <td style="width: 5%;"></td>
                                </tr>
                                <tr>
                                    <td style="width: 5%;"></td>
                                    <td style="width: 35%; text-align: center;">
                                        Joaquín Montaño Salas <br>
                                        Gerente Regional
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td style="width: 45%; text-align: center;">
                                        Mario Aguirre <br>
                                        Gerente Nacional de Seguros Masivos
                                    </td>
                                    <td style="width: 5%;"></td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                </table>
            </div>

            <page><div style="page-break-before: always;">&nbsp;</div></page>

            <div style="width: 770px; border: 0px solid #FFFF00; text-align:justify; font-size: 75%; margin-top: 4px;">
                <p>
                    <b>Discrepancias en la Póliza (Art. 1013) </b>"Si el Tomador o ASEGURADO
                    encuentran que la póliza no concuerda con lo convenido o con lo propuesto,
                    pueden pedir rectificación correspondiente por escrito, dentro de los quince
                    días siguientes a la recepción de la Póliza. Se consideran aceptadas las
                    estipulaciones de esta si durante dicho plazo no se solicita la mencionada
                    rectificación. Si dentro de los quince días siguientes al de la reclamación
                    LA COMPAÑÍA no da curso a la rectificación solicitada o mantiene silencio,
                    se entiende aceptada en los términos de la modificación".
                    <br>
                    <b>Omisión de Aviso (Art. 1030) </b>"LA COMPAÑIA puede liberarse de sus
                    obligaciones cuando EL ASEGURADO o beneficiario, según el caso, omitan dar el
                    aviso dentro del plazo del articulo 1028  con el fin de impedir la comprobación
                    oportuna de las circunstancias del siniestro o el de la magnitud de los daños.
                    (Art.1035 Código de Comercio)".
                    <br>
                    <b>Iniciación y Duración del Contrato. </b>"El presente seguro tendrá una duración
                    de un año a contar de la fecha de inicio de la cobertura, salvo que en las
                    Condiciones Particulares, Certificado de Cobertura  se estipule una duración
                    diferente, el cual se entenderá prorrogado en forma tácita, sucesiva y automática
                    por nuevos e iguales períodos en cada ocasión".
                    <br>
                    <b>Periodo de Gracia. </b>LA COMPAÑÍA concederá al ASEGURADO un periodo de gracia para
                    efectuar el pago de la prima correspondiente sin intereses de treinta (30) días calendario,
                    contados desde el vencimiento de la fecha establecida en el convenio de pago para el
                    pago de la misma.  Si el fallecimiento ocurriese dentro del periodo de gracia, la prima
                    adeudada para completar la anualidad por EL TOMADOR/ ASEGURADO será deducida del
                    beneficio correspondiente.
                    <br>
                    <b>Efecto del no pago de Prima: Terminación del Contrato. </b>Si habiendo vencido el
                    período de gracia fijado por el artículo precedente, la Prima se encontrare impaga,
                    el Contrato de Seguro caducara en forma inmediata, sin necesidad de aviso, notificación
                    o requerimiento alguno, liberándose LA COMPAÑÍA  de toda obligación y responsabilidad
                    derivada de la Póliza.
                    <br>
                    <b>Plazo para Pronunciarse (Art. 1033). </b>LA COMPAÑÍA se pronunciará
                    sobre el derecho de EL ASEGURADO o beneficiario dentro de los treinta
                    (30) días de recibidas la información y evidencias citadas en  el
                    Art.1031 del Código de Comercio.  Se dejará constancia escrita de la
                    fecha de recepción de la información y evidencias a efecto del cómputo
                    del plazo. En plazo de treinta (30) días, fenece con la aceptación o
                    rechazo del siniestro o con la solicitud del asegurador al asegurado
                    que se complementen los requerimientos contemplados en el Art. 1031
                    del Código de Comercio y no vuelve a correr hasta que el asegurado haya
                    cumplido con tales requerimientos.
                    <br>
                    La solicitud de complementos establecidos en el Art. 1031 del Código
                    de Comercio, por parte del Asegurador no podrá extenderse por mas de
                    dos veces a partir de la primera solicitud de informes y evidencias,
                    debiendo pronunciarse dentro del plazo establecido y de manera definitiva
                    sobre el derecho del asegurado, después de la entrega por parte del
                    asegurado del último requerimiento de información.
                    <br>
                    El silencio del asegurador, vencido el término  para pronunciarse o
                    vencidas las solicitudes de complementación, importa la aceptación del
                    reclamo.
                </p>

                <p>
                    <b>Termino para el pago de Siniestro (Art. 1034).</b> En los seguros de
                    vida, el pago se hará dentro de los quince días posteriores al aviso del
                    siniestro o tan pronto sean llenados los requerimientos señalados en el
                    artículo 1031".
                </p>
            </div>
            @if($k<count($query_quest_cl))
                <page><div style="page-break-before: always;">&nbsp;</div></page>
            @endif
            @var $k = $k + 1
        @endforeach
    </div>
</div>