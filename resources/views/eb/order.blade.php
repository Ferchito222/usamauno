<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body style="border: 1px solid #c8c8c8; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 13px;">
<div>
    <div style="text-align: center;">
        <h2>Orden de Cobro</h2>
        <h3>Ecoaguinaldo</h3>
    </div>
    <hr>
    <p style="font-size: 15px; padding: 10px;">
        Estimado Cliente: {{ $header->detail->client->full_name }}
        <br><br>
        Para hacer efectiva la contratación del seguro Ecoaguinaldo, debe presentarse en caja y realizar el pago a
        través de los siguientes datos:
        <br><br>
        Nro. de Certificado: {{ $header->prefix }}-{{ $header->issue_number }}
        <br>
        Nro. de CI: {{ $header->detail->client->dni }} {{ $header->detail->client->extension }}
        <br><br>
        Importe: Bs. 30,00.-
    </p>
    <hr>
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%;">Generado por: {{ $header->user->full_name }}</td>
            <td style="width: 50%; text-align: right;">
                Fecha y hora: {{ date('d/m/Y') }} - {{ date('h:i:s a') }}
            </td>
        </tr>
    </table>
</div>
</body>
</html>