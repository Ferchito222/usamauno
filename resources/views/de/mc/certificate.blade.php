<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>

    <link rel="stylesheet" type="text/css" href="{{ base_path('public/assets/css/bootstrap.css') }}">

</head>
<body style="background: #FFF; font-size: 9px; font-family: Verdana, Geneva, Helvetica, sans-serif;">

<div class="container" style="width: 700px;">
    <img src="{{ base_path('public/' . $fa->observations->last()->user->companies->first()->image) }}"
         style="margin-top: -20px; margin-left: 10px; position: fixed;" width="100">

    <div style="margin-bottom: 0px; display: block; text-align: right; font-size: 90%;">{!! $mc->name !!}</div>
    <div style="margin-top: 0px; display: block; text-align: right; font-size: 80%;">
        <strong>Orden de Atención Medica Nº {{ $answer->medical_certificate_number }}</strong>
    </div>
    <div>
        <table class="table table-condensed">
            <tbody>
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Paciente:</strong>
                </td>
                <td colspan="3" style="padding-top: 1px; padding-bottom: 1px;">
                    {{ $fa->detail->client->full_name }}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Carnet de Identidad:</strong>
                </td>
                <td style="padding-top: 1px; padding-bottom: 1px;">{{ $fa->detail->client->dni }} {{ $fa->detail->client->extension }}</td>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Dirección</strong>
                </td>
                <td style="padding-top: 1px; padding-bottom: 1px;">{{ $fa->detail->client->home_address }}</td>
            </tr>
            <tr>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Teléfono</strong>
                </td>
                <td style="padding-top: 1px; padding-bottom: 1px;">{{ $fa->detail->client->phone_number_home }}</td>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Regional</strong>
                </td>
                <td style="padding-top: 1px; padding-bottom: 1px;">{{ $fa->detail->header->user->city->name }}</td>
            </tr>
            <tr>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Centro de Atención:</strong>
                </td>
                <td colspan="3" style="padding-top: 1px; padding-bottom: 1px;">
                    {{ $answer->careCenter->place_care }}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Persona de Contacto:</strong>
                </td>
                <td colspan="3" style="padding-top: 1px; padding-bottom: 1px;">
                    {{ $answer->contact_person }}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 1px; padding-bottom: 1px;">
                    <strong>Fecha de Atención:</strong>
                </td>
                <td colspan="3" style="padding-top: 1px; padding-bottom: 1px;">
                    {{ date('d-m-Y', strtotime($answer->date_attention)) }}
                </td>
            </tr>
            </tbody>
        </table>

        <div class="label label-default" style="font-size: 60%; color: black; background-color: white;">
            TIPO DE EXAMEN A REALIZAR: *
        </div>

        @foreach ($mc->certificateQuestionnaires as $cq => $certificateQuestionnaire)
            <div style="margin-top: 0px; font-size: 60%;">
                <span style="font-weight: bold;">{{ $cq + 1 }}.</span>
                {{ $certificateQuestionnaire->questionnaire->title }}
            </div>

            <div class="row">
                @foreach ($certificateQuestionnaire->questions as $question)
                    @var $check = '';
                    @var $response = false;

                    @if (array_key_exists($certificateQuestionnaire->questionnaire->id, $answer->response))
                        @if (array_key_exists($question->id, $answer->response[$certificateQuestionnaire->questionnaire->id]))
                            @if(array_key_exists('response', $answer->response[$certificateQuestionnaire->questionnaire->id][$question->id]))
                                @if ($answer->response[$certificateQuestionnaire->questionnaire->id][$question->id]['response'] === 'true')
                                    @var $check = 'X';
                                    @var $response = true;
                                @endif
                            @endif
                        @endif
                    @endif

                    <div class="table-bordered" style="padding: 1px 0;">
                        <table>
                            <tr>
                                <td style="width:12px; border: 2px solid #000; padding: 2px;">
                                    <div style="width: 100%; height: 10px; padding-top: -12px; font-size: 80%; font-weight: bold; text-align: center;">{{ $check }}</div>
                                </td>
                                <td style="padding-left: 5px;">
                                    <strong>{{ $question->question }}</strong>
                                </td>
                            </tr>
                        </table>
                        @if ($response && ($question->type === 'TX' || $question->type === 'TA'))
                            <table>
                                <tr>
                                    <td>
                                        @if(key_exists('text', $answer->response[$certificateQuestionnaire->questionnaire->id][$question->id]))
                                            {{ $answer->response[$certificateQuestionnaire->questionnaire->id][$question->id]['text'] }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </div>

                @endforeach
            </div>
        @endforeach
    </div>

    <p>
        Portar documento de Identidad.<br>
        <strong>EXAMEN DE SANGRE</strong><br />
        - Asistir en ayunas.<br />
        <strong>ANALISIS DE ORINA</strong><br />
        - La recolección de la primera orina para el estudio, deberá realizarse en el laboratorio, no se recibirá ninguna
        muestra colectada por el propuesto asegurado en su casa u otro lugar.<br />
        - En el caso de mujeres, no realizar la prueba si se encuentran menstruando (Período). Se deben  esperar 3
        días  después  de  la finalización del ciclo menstrual para realizar el estudio.<br />
        <strong>ELECTROCARDIOGRAMA EN ESFUERZO</strong><br />
        - Para esta prueba el propuesto asegurado deberá asistir con ropa como o Deportiva. 
    </p>
    <p style="text-align: right;">
        {{ $answer->user->city->name }}, {{ $answer->creation_date }}
    </p>
    <div class="fa-align-right">
        <img src="{{ base_path('public/assets/files/'.$answer->user->firm) }}" style="margin-left: 250px;" width="120">
    </div>
</div>

</body>
</html>