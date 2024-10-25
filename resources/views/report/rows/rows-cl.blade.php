@if($format === 'xls')
    <meta charset="utf-8">
@else
    @var $user_profile = $user->profile->first()->slug;
    @var $user_permissions = $user->permissions->pluck('slug')->toArray();
@endif

<div style="{{ $format !== 'xls' ? 'width: 100%;' : '' }} overflow: scroll;" ng-controller="ReportController" ng-init="uploadFile();">
    <table class="table-bordered table-hover gridview" border="1" {{ $format !== 'xls' ? 'width=200%' : '' }} style="font-size: 75%;">
        <thead>
        <tr style="font-weight: bold;">
            <th>NRO. DE CERTIFICADO</th>
            <th>COBERTURA</th>
            <th>PARTICIPACIÓN</th>
            <th>PRODUCTO</th>
            <th>CLIENTE</th>
            <th style="width: 100px;">C.I.</th>
            <th>MONTO SOLICITADO / MONEDA</th>
            <th>SALDO DEUDOR</th>
            <th>TOTAL MONTO ACUMULADO</th>
            <th>PLAZO DEL CREDITO /<br>TIPO DEL PLAZO</th>
            <th>TITULAR</th>
            <th>USUARIO</th>
            <th>SUCURSAL / AGENCIA</th>
            <th>FECHA DE INGRESO</th>
            <th>COBERTURAS</th>
            <th>EMITIDO</th>
            <th>FECHA DE EMISIÓN</th>
            <th>ESTADO COMPAÑIA</th>
            <th>MOTIVO ESTADO COMPAÑIA</th>
            <th>ESTADO BANCO</th>
            <th>FACULTATIVO OBSERVACIÓN</th>
            <th>FECHA RESPUESTA FINAL COMPAÑIA</th>
            <th>DIAS EN PROCESO</th>
            <th>DURACION TOTAL DEL CASO</th>
            <th>PORCENTAJE EXTRAPRIMA</th>
            <th>TASA FINAL</th>
            <th>NUMERO DE OPERACION</th>
            <th>ANULADO</th>
            <th>ANULADO POR</th>
            <th>MOTIVO DE ANULACIÓN</th>
            <th>FECHA DE ANULACIÓN</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($credit_lines as $credit_line)
            @var $rows   = $credit_line->clients->count()
            @var $header = $credit_line->header

            @foreach($credit_line->clients as $index => $client)
                @var $detail = !is_null($header) ? $header->details->where('client.dni', $client->dni)->first() : null

                <tr>
                    <td style="font-weight: bold;">
                        {{ !is_null($header) ? $header->prefix . '-' . $header->issue_number : '---' }}
                    </td>
                    <td>{{ config('base.credit_line.coverages.' . $credit_line->coverage) }}</td>
                    <td>{{ $client->percentage_credit }} %</td>
                    <td>
                        {{ !is_null($header) ? $header->creditProduct->name : config('base.credit_line.policy_type.' . $credit_line->policy_type) }}
                    </td>
                    <td>
                        {{ !is_null($detail) ? $detail->client->full_name : '---' }}
                    </td>
                    <td>
                        @if(!is_null($detail))
                            {{ $detail->client->dni . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                            {{ $detail->client->extension }}
                        @else
                            {{ $client->dni . (! empty($client->complement) ? '-' . $client->complement : '') }}
                            {{ $client->extension }}
                        @endif
                    </td>
                    <td>{{ !is_null($header) ? number_format($header->amount_requested, 2) . $header->currency . '.' : '---' }}</td>
                    <td>{{ !is_null($header) ? number_format($header->balance, 2) . $header->currency . '.' : '---' }}</td>
                    <td>{{ !is_null($header) ? number_format($header->cumulus, 2) . $header->currency . '.' : '---' }}</td>
                    <td>{{ !is_null($header) ? $header->term . ' ' . config('base.term_types.' . $header->type_term) : '---' }}</td>
                    <td>{{ !is_null($detail) ? config('base.headlines.' . $detail->headline) : ($client->headline === 1 ? 'Deudor' : 'Codeudor') }}</td>
                    <td>{{ !is_null($header) ? $header->user->full_name : $credit_line->user->full_name }}</td>
                    <td>
                        @if(!is_null($header))
                            {{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }}
                            /
                            {{ $header->user->agency->name }}
                        @else
                            {{ $credit_line->user->city->name . ' / ' . $credit_line->user->agency->name }}
                        @endif
                    </td>
                    <td>{{ date_format_report(!is_null($header) ? $header->created_at : $credit_line->created_at) }}</td>
                    <td style=" ">
                        @if(!is_null($detail))
                            @foreach($detail->coverages as $coverage)
                                <span style="font-weight: bold">*</span>
                                {{ config('base.client_coverages.' . $coverage->coverage) }}
                                <br>
                            @endforeach
                        @else
                            ---
                        @endif
                    </td>
                    <td>{{ !is_null($header) ? ($header->issued ? 'SI' : 'NO') : '---' }}</td>
                    <td>{{ !is_null($header) ? ($header->issued ? date_format_report($header->date_issue) : '-') : '---' }}</td>
                    <td>{{ !is_null($header) && !is_null($detail) ? getDeStateCompany($header, $detail) : '---' }}</td>
                    <td>{{ !is_null($header) && !is_null($detail) ? getDeReasonStateCompany($header, $detail) : '---' }}</td>
                    <td>{{ !is_null($header) ? ($header->issued ? 'Emitido' : 'No Emitido') : '---' }}</td>
                    <td>{{ !is_null($header) && !is_null($detail) ? getDeObservationFacultative($detail) : '---' }}</td>
                    <td>
                        @if(!is_null($header) && !is_null($detail))
                            @if($header->facultative
                                && (! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR')
                            )
                                {{ $detail->facultative->updated_at->format('d/m/Y H:i') }}
                            @elseif($header->facultative
                                    && (! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PE'
                                    && $detail->facultative->observations->count() > 0)
                            )
                                {{ $detail->facultative->observations->last()->created_at->format('d/m/Y H:i') }}
                            @endif
                        @else
                            ---
                        @endif
                    </td>
                    <td>{{ !is_null($header) && !is_null($detail) ? getDeDaysInProcess($header, $detail) : '---' }}</td>
                    <td>{{ !is_null($header) ? getDeTotalDuration($header) . ' dias' : '---' }}</td>
                    <td>
                        @if(!is_null($header) && !is_null($detail))
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'
                                    && $detail->facultative->approved
                                    && $detail->facultative->surcharge))
                                {{ $detail->facultative->percentage }} %
                            @else
                                0 %
                            @endif
                        @else
                            {{ $client->percentage }} %
                        @endif
                    </td>
                    <td>
                        @if(!is_null($header) && !is_null($detail))
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'
                                    && $detail->facultative->approved
                                    && $detail->facultative->surcharge))
                                {{ number_format(($header->creditProduct->slug !== 'PCA' ? $detail->facultative->final_rate * 12 : $detail->facultative->final_rate), 3) }}
                            @else
                                {{ number_format(($header->creditProduct->slug !== 'PCA' ? $detail->rate * 12 : $detail->rate), 3) }}
                            @endif
                        @else
                            {{ number_format(($client->rate + (($client->rate * $client->percentage) / 100)), 3) }}
                        @endif
                    </td>
                    <td>{{ !is_null($header) ? $header->operation_number : $credit_line->number_line }}</td>
                    <td>{{ !is_null($header) ? ($header->canceled ? 'SI' : 'NO') : '---' }}</td>
                    <td>{{ !is_null($header) ? (is_null($header->cancellation) ? '-' : $header->cancellation->user->full_name) : '---' }}</td>
                    <td>{{ !is_null($header) ? (is_null($header->cancellation) ? '-' : $header->cancellation->reason) : '---' }}</td>
                    <td>{{ !is_null($header) ? (is_null($header->cancellation) ? '-' : date_format_report($header->cancellation->created_at)) : '---' }}</td>
                    <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                        @if(!is_null($header) && $format !== 'xls')
                            <a href="{{ route('certificate.show', [
                                'code'      => 'de',
                                'rp_id'     => $rp_id,
                                'type'      => 'certificate',
                                'header_id' => encode($header->id),
                                'format'    => 'pdf',
                            ]) }}" target="_blank" class="btn btn-info" style="font-size: 90%;">
                                Ver Certificado de Emisión
                            </a>
                        @endif
                    </td>
                    <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                        @if ($format !== 'xls')
                            @var $moves = route('de.creditLine.moves', ['id' => encode($credit_line->id)])

                            <a href="javascript:window.open('{{$moves}}', 'Líneas de Credito', 'width=600, height=600')" class="btn btn-warning" style="font-size: 1rem; font-weight: bold;">Movimientos</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>

    @if($format !== 'xls')
        <input type="hidden" id="url-upload" value="{{ route('de.upload.certificate', ['rp_id' => $rp_id, 'header_id' => ':header_id']) }}">
    @endif
</div>