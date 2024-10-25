<h2>
    <span class="label label-default">
        Sucursal -
        @if($data['header']->placeIssuance instanceof \Sibas\Entities\City)
            {{ $data['header']->placeIssuance->name }}
        @else
            {{ $data['header']->user->city->name }}
        @endif
    </span>
</h2>

<h3>Certificado No. {{ $data['header']->prefix }}-{{ $data['header']->issue_number }}</h3>

<div class="table-responsive">
    <div style="text-align: justify; padding: 10px 50px 10px 10px; color: #3A3A3A; font-weight: bold;">
        Estimado Oficial de Negocios:
        <br><br>
        Mediante el presente, comunicamos a usted que la aseguradora ha afiliado al cliente de manera automática, al
        respecto recomendamos lo siguiente:
        <br><br>
        <ul style="list-style-type: decimal;">
            <li>Imprimir del sistema Abrenet el Certificado de Cobertura y Declaración Jurada de Salud.</li>
            <li>Hacer Firmar con el cliente todas las copias.</li>
            <li>Entregar una copia firmada al cliente.</li>
            <li>Las otras copias deben guardarse en el file de crédito.</li>
            <li>Verificar que al cliente se le cobre la prima de forma correcta</li>
            <li>Verificar que el contrato de crédito cuente con la cláusula de Seguro de Desgravamen.</li>
            <li>Adjuntar en el sistema Abrenet la DJS firmada por el cliente junto al correspondiente Certificado de
                Cobertura en el reverso de la hoja.
            </li>
        </ul>
    </div>
    <hr>
    <table class="table table-striped table-condensed">
        <tbody>
        <tr class="info">
            <td>
                <strong>CLIENTE (s):</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>
                    @foreach($data['header']->details as $key => $detail)
                        {{ $key === 0 ? 'T' : 'C' . $key }}: {{ $detail->client->full_name }}
                        <br>
                    @endforeach
                </strong>
            </td>
        </tr>
        </tbody>
    </table>
</div>
