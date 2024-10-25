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
        La solicitud de afiliación de referencia está pendiente por los siguientes motivos:
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
                <strong>OBSERVADO ({{ $data['fa']->observations->last()->state->state }})</strong>
            </td>
        </tr>
        <tr class="info">
            <td>
                <strong>DETALLES:</strong>
            </td>
        </tr>
        <tr>
            <td>
                @if ($data['fa']->observations->last()->state->slug === 'me')
                    <a href="{{ route('de.fa.mc.show', [
                      'rp_id' => encode($data['rp_id']),
                      'id'    => encode($data['fa']->id)
                    ]) }}" class="btn btn-info">Orden de atención médica</a>
                @else
                    <strong>{{ $data['fa']->observations->last()->observation }}</strong>
                @endif
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
        {{--<tr class="info">
            <td>
                <strong>VEHICULO:</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{ $data['fa']->detail->vehicleType->vehicle }}</strong>
                <br>
                Placa: <strong>{{ $data['fa']->detail->license_plate }}</strong>
            </td>
        </tr>--}}
        </tbody>
    </table>
    <hr>
    <p style="text-align: justify; padding: 10px; font-weight: bold; color: #3A3A3A;">
        En ese sentido, le pedimos por favor comunicarse con su (s) clientes para que cumplan con lo solicitado por la
        aseguradora, es oportuno mencionar que hasta que la aseguradora no de su aceptación por escrito, el (los)
        cliente (s) no tiene (n) cobertura y no se debe cobrar prima de seguro.
    </p>
</div>