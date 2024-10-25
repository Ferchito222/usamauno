<table class="table table-bordered table-striped table-condensed" style="font-size: 80%;">
    <thead>
    <tr>
        <th class="text-center">Nro. de Certificado</th>
        <th class="text-center">Cliente</th>
        <th class="text-center">CI</th>
        <th class="text-center">Fecha de Ingreso</th>
        <th class="text-center">Creado Por</th>
        <th class="text-center">Emitido</th>
        <th class="text-center">Fecha de Emision</th>
        <th class="text-center">Anulado</th>
        <th class="text-center">Fecha de Anulacion</th>

        <th class="text-center"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($headers as $header)
        <tr>
            <td class="text-center">{{ $header->prefix }}-{{ $header->issue_number }}</td>
            <td class="text-center">{{ $header->detail->client->full_name }}</td>
            <td class="text-center">
                {{ $header->detail->client->dni }} {{ $header->detail->client->complement }} {{ $header->detail->client->extension }}
            </td>
            <td class="text-center">{{ $header->created_at->format('d/m/Y h:i a') }}</td>
            <td class="text-center">{{ $header->user->full_name }}</td>
            <td class="text-center">{{ $header->issued ? 'SI' : 'NO' }}</td>
            <td class="text-center">{{ $header->date_issue->format('d/m/Y h:i a') }}</td>
            <td class="text-center">{{ $header->canceled ? 'SI' : 'NO' }}</td>
            <td class="text-center">
                @if(! is_null($header->cancellation))
                    {{ $header->cancellation->created_at->format('d/m/Y h:i a') }}
                @else
                    -
                @endif
            </td>
            <td class="text-center">
                @if(request()->route()->getParameter('format') !== 'xls')
                    @if($header->issued)
                        <a href="{{ route('certificate.show', [
                            'code'      => 'eb',
                            'rp_id'     => $rp_id,
                            'type'      => 'certificate',
                            'header_id' => encode($header->id),
                            'format'    => 'print',
                        ]) }}" class="btn btn-info" ng-click="openCertificate($event)">
                            Ver Certificado
                        </a>
                    @else
                        <a href="{{ route('cashiers::order.edit', [
                            'code'      => $code,
                            'rp_id'     => $rp_id,
                            'header_id' => encode($header->id),
                        ]) }}" class="btn btn-success" ng-click="orderEdit($event)">Emitir</a>
                    @endif
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>