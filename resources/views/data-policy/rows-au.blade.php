<table class="table-bordered table-striped table-hover" width="100%" style=" font-size: 75%;">
    <thead>
    <tr style="font-weight: bold;">
        <th>NRO. DE PÓLIZA</th>
        <th>CLIENTE</th>
        <th style="width: 100px;">C.I.</th>
        <th>PRIMA TOTAL</th>
        <th>PLAZO DEL CREDITO /<br>TIPO DEL PLAZO</th> 
        <th>AÑO</th>
        <th>PLACA</th>
        <th>0 KM</th>
        <th>VALOR ASEGURADO</th>
        <th>USUARIO</th>
        <th>SUCURSAL / AGENCIA</th>
        <th>FECHA DE INGRESO</th>
        <th>EMITIDO</th>
        <th>FECHA DE EMISIÓN</th>
        <th>ESTADO COMPAÑIA</th>
        <th>ESTADO BANCO</th>
        <th>TASA FINAL</th>
        <th>NUMERO DE OPERACION</th>
        <th></th>
        <th>ACCIÓN</th>
    </tr>
    </thead>
    <tbody>
    @foreach($headers as $header)
        @foreach($header->details as $detail)
            <tr>
                <td style="font-weight: bold;">
                    @if($header->type === 'Q')
                        {{ $header->quote_number }}
                    @else
                        {{ $header->prefix }}-{{ $header->issue_number }}
                    @endif
                </td>
                <td>{{ $header->client->full_name }}</td>
                <td>
                    {{ $header->client->dni
                        . (! empty($header->client->complement) ? '-' . $header->client->complement : '') }}
                    {{ $header->client->extension }}
                </td>
                <td>{{ number_format($header->total_premium, 2) }} {{ $header->currency }}.</td>
                <td>{{ $header->term }} {{ config('base.term_types.' . $header->type_term) }}</td>
                
                <td>{{ $detail->year }}</td>
                <td>{{ $detail->license_plate }}</td>
                <td>{{ $detail->mileage==0?'SI':'NO' }}</td>
                <td>{{ $detail->insured_value }}</td>
                <td>{{ $header->user->full_name }}</td>
                <td>{{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }}
                    /
                    {{ $header->user->agency->name }}
                </td>
                <td>{{ date_format_report($header->created_at) }}</td>
                <td>{{ $header->issued ? 'SI' : 'NO' }}</td>
                <td>{{ $header->issued ? date_format_report($header->date_issue) : '-' }}</td>
                <td>{{ getDeStateCompany($header, $detail) }}</td>
                <td>{{ $header->issued ? 'Emitido' : 'No Emitido' }}</td>
                <td>
                    {{ $detail->rate }}
                </td>
                <td>{{ $header->operation_number }}</td>
                @include('data-policy.actions')
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>