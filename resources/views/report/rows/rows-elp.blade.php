@if(request()->route()->getParameter('format') === 'xls')
    <meta charset="utf-8">
@endif

<table class="table-bordered table-striped table-hover" width="100%" style="font-size: 75%;">
    <thead>
    <tr style="font-weight: bold;">
        <th style="width: auto;">
            @if(request()->route()->getParameter('type') === 'QTE')
                NRO. DE COTIZACIÓN
            @else
                NRO. DE CERTIFICADO
            @endif
        </th>
        <th>CLIENTE DE ECOFUTURO</th>
        <th>CLIENTE</th>
        <th>C.I.</th>
        @if(request()->route()->getParameter('type') !== 'QTE')
            <th>PRIMA ANUAL</th>
            <th>MODALIDAD DE PAGO</th>
        @endif
        <th>USUARIO</th>
        <th>SUCURSAL</th>
        <th>AGENCIA</th>
        <th>FECHA DE INGRESO</th>
        @if(request()->route()->getParameter('type') !== 'QTE')
            <th>ESTADO</th>
            <th>EMITIDO</th>
            <th>FECHA DE EMISIÓN</th>
            <th>ANULADO</th>
            <th>FECHA DE ANULACIÓN</th>
        @endif
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($headers as $header)
        <tr>
            <td>
                @if(request()->route()->getParameter('type') === 'QTE')
                    {{ $header->quote_number }}
                @else
                    {{ $header->prefix }}-{{ $header->issue_number }}
                @endif
            </td>
            <td style="width: auto;">{{ $header->detail->client_eco ? 'SI' : 'NO' }}</td>
            <td style="width: auto;">{{ $header->detail->client->full_name }}</td>
            <td style="width: auto;">{{ $header->detail->client->dni }} {{ $header->detail->client->extension }}</td>
            @if(request()->route()->getParameter('type') !== 'QTE')
                <td style="width: auto;">{{ number_format($header->detail->premium, 2) }}</td>
                <td style="width: auto;">{{ config('base.payment_methods.' . $header->detail->payment_method) }}</td>
            @endif
            <td style="width: auto;">{{ $header->user->full_name }}</td>
            <td style="width: auto;">{{ $header->user->city->name }}</td>
            <td style="width: auto;">{{ $header->user->agency->name }}</td>
            <td style="width: auto;">{{ date_format_report($header->created_at) }}</td>
            @if(request()->route()->getParameter('type') !== 'QTE')
                <td style="width: auto;">{{ is_null($header->state) ? '' : $header->state->state }}</td>
                <td style="width: auto;">{{ $header->issued ? 'SI' : 'NO' }}</td>
                <td style="width: auto;">{{ $header->issued ? date_format_report($header->date_issue) : '-' }}</td>
                <td style="width: auto;">{{ $header->canceled ? 'SI' : 'NO' }}</td>
                <td style="width: auto;">{{ is_null($header->cancellation) ? '-' : date_format_report($header->cancellation->created_at) }}</td>
            @endif
            @if(request()->route()->getParameter('format') !== 'xls')
                <td>
                    <a href="{{ route('certificate.show', [
                    'code'      => $code,
                    'rp_id'     => $rp_id,
                    'type'      => (request()->route()->getParameter('type') === 'QTE' ? 'slip' : 'certificate'),
                    'header_id' => encode($header->id),
                    'format'    => 'print',
                ]) }}" class="btn btn-info" style="font-size: 90%;"
                       ng-click="openCertificate($event)">
                        {{ (request()->route()->getParameter('type') === 'QTE' ? 'Ver Slip de Cotización' : 'Ver Certificado de Emisión') }}
                    </a>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>