<h2>
    <span class="label label-default">
        Sucursal -
        @if($data['fa']->detail->header->placeIssuance instanceof \Sibas\Entities\City)
            {{ $data['fa']->detail->header->placeIssuance->name }}
        @else
            {{ $data['fa']->detail->header->user->city->name }}
        @endif
    </span>
</h2>

<h3>Certificado No. {{ $data['fa']->detail->header->prefix }}-{{ $data['fa']->detail->header->issue_number }}</h3>

<div class="table-responsive">
    <p style="text-align: justify; padding: 10px; font-weight: bold; color: #3A3A3A;">
        Estimado Oficial de Negocios:
        <br><br>
        A continuación encontrará un resumen del pronunciamiento de la aseguradora sobre la afiliación al caso de
        referencia:
    </p>
    <hr>
    <table class="table table-striped table-condensed">
        <tbody>
        <tr class="info">
            <td>
                <strong>ESTADO DE LA SOLICITUD</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ $data['fa']->approved ? 'APROBADO' : 'RECHAZADO' }}</strong>
            </td>
        </tr>
        @if ($data['fa']->approved)
            <tr class="info">
                <td>
                    <strong>TASA DE RECARGO</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>{{ $data['fa']->surcharge ? 'SI' : 'NO' }} - {{ $data['fa']->percentage }} %</strong>
                </td>
            </tr>
            <tr class="info">
                <td>
                    <strong>TASA RECARGO</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>{{ $data['fa']->final_rate }}</strong>
                </td>
            </tr>
        @endif
        <tr class="info">
            <td>
                <strong>DETALLES:</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ $data['fa']->observation }}</strong>
            </td>
        </tr>
        <tr class="info">
            <td>
                <strong>CLIENTE:</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ $data['client']->full_name }}</strong>
            </td>
        </tr>
        </tbody>
    </table>
    <hr>
    <div style="text-align: justify; padding: 10px 50px 10px 10px; color: #3A3A3A;">
        <span style="font-weight: bold;">Al respecto, recomendamos:</span>
        <br>
        <ul style="list-style-type: decimal; font-weight: bold;">
            <li>Entrar al Sistema Abrenet, emitir e Imprimir el Certificado de Cobertura y Declaración Jurada de Salud.</li>
            <li>Hacer Firmar con el cliente todas las copias.</li>
            <li>Entregar una copia firmada al cliente.</li>
            <li>Las otras copias deben guardarse en el file de crédito.</li>
            <li>Adjuntar en el sistema Abrenet la DJS firmada por el cliente junto al correspondiente Certificado de
                Cobertura en el reverso de la hoja.
            </li>
        </ul>
        <span style="font-weight: bold;">Adicionalmente por favor considerar:</span>
        <br>
        <ul style="list-style-type: decimal;">
            <li><strong>Si la afiliación fue aceptada en condiciones normales:</strong> Se debe declarar al cliente en
                los listados mensuales de pago de primas y verificar que el contrato de crédito tenga la cláusula de
                Seguro de Desgravamen aceptado en condiciones normales (clausula estándar)
            </li>
            <li><strong>Si la afiliación fue aceptada con recargo de prima:</strong> Se debe comunicar por escrito este
                aspecto al cliente, se lo debe reportar en los listados de pago de primas considerando el recargo en la
                prima, modificar el contrato de préstamo en la cláusula de Desgravamen.
            </li>
            <li><strong>Si la Afiliación fue aceptada sin la cobertura de Incapacidad Total y Permanente o con Alguna
                    Exclusión Especial:</strong> Se debe comunicar por escrito este aspecto al cliente, se lo debe
                reportar en los listados de pago de primas, modificar el contrato de préstamo en la cláusula de
                Desgravamen.
            </li>
            <li><strong>Si la afiliación es mancomunada o con codeudor:</strong> Se debe comunicar por escrito este
                aspecto a los clientes, se los debe reportar en los listados de pago de primas considerando la
                proporción de prima que debe cancelar cada participante, modificar el contrato de préstamo en la
                cláusula de Desgravamen.
            </li>
            <li><strong>Si la Afiliación fue Rechazada:</strong> Se debe comunicar por escrito este aspecto al cliente,
                no se deben cobrar primas, dejar claro en el contrato de préstamo en la cláusula de Desgravamen que el
                cliente no tiene cobertura.
            </li>
        </ul>
    </div>
</div>