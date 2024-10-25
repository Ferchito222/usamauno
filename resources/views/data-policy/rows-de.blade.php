<table class="table-bordered table-striped table-hover" width="100%" style=" font-size: 75%;">
    <thead>
    <tr style="font-weight: bold;">
        <th>NRO. DE PÓLIZA</th>
        <th>CLIENTE</th>
        <th>C.I.</th>
        <th>COBERTURA</th>
        <th>MONTO SOLICITADO</th>
        <th>PLAZO DEL CRÉDITO</th>
        <th>USUARIO</th>
        <th>FECHA DE CREACIÓN</th>
        <th>FACULTATIVO</th>
        <th>ANULADO</th>
        <th>EMITIDO</th>
        <th>FECHA DE EMISIÓN</th>
        <th></th>
        <th>ACCIÓN</th>
    </tr>
    </thead>
    <tbody>
    @foreach($headers as $header)
        @foreach($header->details as $detail)
            <tr>
                <td>{{ $header->prefix }}-{{ $header->issue_number }}</td>
                <td>{{ $detail->client->full_name }}</td>
                <td>{{ $detail->client->dni }} {{ $detail->client->extension }}</td>
                <td>{{ $header->coverage->name }}</td>
                <td>{{ number_format($header->amount_requested, 2) }} {{ config('base.currencies.' . $header->currency) }}</td>
                <td>{{ $header->term }} {{ config('base.term_types.' . $header->type_term) }}</td>
                <td>{{ $header->user->full_name }}</td>
                <td>{{ date_format_report($header->created_at) }}</td>
                <td>{{ $header->facultative ? 'SI' : 'NO' }}</td>
                <td>{{ $header->canceled ? 'SI' : 'NO' }}</td>
                <td>{{ $header->issued ? 'SI' : 'NO' }}</td>
                <td>{{ $header->issued ? date_format_report($header->date_issue) : '-' }}</td>
                @include('data-policy.actions')
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>