<table class="table-bordered table-striped table-hover" width="100%" style=" font-size: 75%;">
    <thead>
    <tr style="font-weight: bold;">
        <th>Nro. de Póliza</th>
        <th>Cliente</th>
        <th>C.I.</th>
        <th>Uso</th>
        <th>Número de Serie</th>
        <th>Ciudad</th>
        <th>Valor Asegurado</th>
        <th>Forma de Pago</th>
        <th>Usuario</th>
        <th>Sucursal / Agencia</th>
        <th>Fecha de Ingreso</th>
        <th></th>
        <th>ACCIÓN</th>
    </tr>
    </thead>
    <tbody>
    @foreach($headers as $header)
        @foreach($header->details as $detail)
            <tr>
                <td>
                    @if($header->type === 'Q')
                        {{ $header->quote_number }}
                    @else
                        {{ $header->prefix }}-{{ $header->issue_number }}
                    @endif
                </td>
                <td>{{ $header->client->full_name }}</td>
                <td>{{ $header->client->dni }} {{ $header->client->extension }}</td>
                <td>{{ $detail->property_use }}</td>
                <td>{{ $detail->serial_number }}</td>
                <td>{{ $detail->city }}</td>
                <td>{{ number_format($detail->insured_value, '2') }} {{ $header->currency }}</td>
                <td>{{ config('base.payment_methods.' . $header->payment_method) }}</td>
                <td>{{ $header->user->full_name }}</td>
                <td>
                    {{ ! is_null($header->user->city) ? $header->user->city->name : '' }}
                    {{ ! is_null($header->user->agency) ? '/ ' . $header->user->agency->name : '' }}
                </td>
                <td>
                    {{ $header->created_date }}
                </td>
                @include('data-policy.actions')
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>