@if($format === 'xls')
    <meta charset="utf-8">
@else
    @var $user_profile = $user->profile->first()->slug;
    @var $user_permissions = $user->permissions->pluck('slug')->toArray();
@endif

<div style="{{ $format !== 'xls' ? 'width: 100%;' : '' }} overflow: scroll;" ng-controller="ReportController" ng-init="uploadFile();">
    <table class="table-bordered table-hover gridview" border="1" {{ $format !== 'xls' ? 'width=230%' : '' }} style="font-size: 75%;">
        <thead>
        <tr style="font-weight: bold;">
            <th>
                @if($type === 'QTE')
                    NRO. DE COTIZACIÓN
                @else
                    NRO. DE CERTIFICADO
                @endif
            </th>
            <th>COBERTURA</th>
            @if($type !== 'QTE')
                <th>PARTICIPACIÓN</th>
            @endif
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
            <th>TIPO DE SOLICITUD</th>
            @if($type !== 'QTE')
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
                <th>PORCENTAJE EXTRAPRIMA BANCO</th>
                <th>PORCENTAJE EXTRAPRIMA CIA</th>
                <th>TASA ACTUAL</th>
                <th>TASA RECARGO</th>
                <th>TARIFICACION MONETARIA</th>
                <th>NUMERO DE OPERACION</th>
                <th>COBERTURA DE DESEMPLEO</th>
                <th>TASA DESEMPLEO</th>
                <th>ANULADO</th>
                <th>ANULADO POR</th>
                <th>MOTIVO DE ANULACIÓN</th>
                <th>FECHA DE ANULACIÓN</th>
            @endif
            @if($format !== 'xls')
                <th></th>
            @endif
            @if(in_array($type, [ 'ISS', 'BW' ]) && $format !== 'xls')
                <th>Certificado Adjunto</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($headers as $header)
            @var $rows = $header->details->count()

            @foreach($header->details as $index => $detail)
                <tr>
                    <td style="font-weight: bold;">
                        @if($type === 'QTE')
                            {{ $header->quote_number }}
                        @else
                            {{ explode('-',$header->policy_number)[0] }}-{{ $header->issue_number }}{{$header->version_number}}
                        @endif
                    </td>
                    <td>{{ $header->coverage->name }}</td>
                    @if($type !== 'QTE')
                        <td>{{ $detail->percentage_credit }} %</td>
                    @endif
                    <td>{{ $header->creditProduct->name }}</td>
                    <td>{{ $detail->client->full_name }}</td>
                    <td>
                        {{ $detail->client->dni
                            . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                        {{ $detail->client->extension=='SE'?'':$detail->client->extension }}
                    </td>
                    <td>{{ number_format($header->amount_requested, 2) }} {{ $header->currency }}.</td>
                    <td>{{ number_format($detail->balance, 2) }} BS.</td>
                    <td>{{ number_format($detail->cumulus, 2) }} BS.</td>
                    <td>{{ $header->term }} {{ config('base.term_types.' . $header->type_term) }}</td>
                    <td>{{ config('base.headlines.' . $detail->headline) }}</td>
                    <td>{{ $header->user->full_name }}</td>
                    <td>
                        {{ $header->retailerCityAgency->retailerCity->city->name }} / {{ $header->retailerCityAgency->agency->name }}
                    </td>
                    <td>{{ date_format_report($header->created_at) }}</td>
                    <td>{{ !is_null($header->type_of_request)?config('base.types_of_requests.'.$header->type_of_request):'Sin Información' }}</td>
                    @if($type !== 'QTE')
                        <td style=" ">
                            @if(isFacultativeAccidentalDeath($header, $detail))
                                MUERTE ACCIDENTAL
                            @else
                                @foreach($detail->coverages as $coverage)
                                    
                                    @if($coverage->coverage == 'CTPD')
                                        @if($coverage->granted == true)
                                        <span style="font-weight: bold">*</span>
                                            {{ config('base.client_coverages.' . $coverage->coverage) }}
                                        @endif
                                    @elseif($coverage->coverage != 'CF')
                                    <span style="font-weight: bold">*</span>
                                        {{ config('base.client_coverages.' . $coverage->coverage) }}
                                    @endif
                                    <br>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $header->issued ? 'SI' : 'NO' }}</td>
                        <td>{{ $header->issued ? date_format_report($header->date_issue) : '-' }}</td>
                        <td>{{ getDeStateCompany($header, $detail) }}</td>
                        <td>{{ getDeReasonStateCompany($header, $detail) }}</td>
                        <td>{{ $header->issued ? 'Emitido' : 'No Emitido' }}</td>
                        <td>{{ getDeObservationFacultative($detail) }}</td>
                        <td>
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
                            @elseif(is_null($detail->facultative) && $header->issued)
                                {{date('d/m/Y H:i', strtotime($header->date_issue))}}
                            @endif
                        </td>
                        <td>{{ getDeDaysInProcess($header, $detail) }}</td>
                        <td>{{ getDeTotalDuration($header) }} dias</td>
                        <td>
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'
                                    && $detail->facultative->approved
                                    && $detail->facultative->surcharge))
                                {{ $detail->facultative->percentage }} %
                            @else
                                0 %
                                
                            @endif
                        </td>
                        <td>
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'
                                    && $detail->facultative->approved))
                                {{ $detail->facultative->percentage_bank }} %
                            @else
                                0 %
                                
                            @endif
                        </td>
                        <td>
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'
                                    && $detail->facultative->approved))
                                {{ $detail->facultative->percentage_cia }} %
                            @else
                                0 %
                                
                            @endif
                        </td>
                        <td>
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'
                                    && $detail->facultative->approved))
                                {{ number_format($detail->facultative->current_rate, 4) }}
                            @else
                                {{ number_format($detail->rate, 4) }}
                            @endif
                        </td>
                        <td>
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'
                                    && $detail->facultative->approved
                                    && $detail->facultative->surcharge))
                                {{ number_format($detail->facultative->final_rate, 4) }}
                            @else
                                0.0000
                            @endif
                        </td>
                        <td>
                            @if($header->facultative
                                && ( ! is_null($detail->facultative)
                                    && $detail->facultative->state === 'PR'))
                                {{ $detail->facultative->monetary_pricing }}
                            @endif
                        </td>
                        <td>
                            @if ($header->creditProduct->slug == 'PCA')
                                {{ $header->card_number }}
                            @else
                                {{ $header->operation_number }}
                            @endif
                        </td>
                        <td>{{ $detail->cesantia?'SI':'NO' }}</td>
                        <td>{{ $detail->rate_cesantia }}</td>
                        <td>{{ $header->canceled ? 'SI' : 'NO' }}</td>
                        <td>{{ is_null($header->cancellation) ? '-' : $header->cancellation->user->full_name }}</td>
                        <td>{{ is_null($header->cancellation) ? '-' : $header->cancellation->reason }}</td>
                        <td>{{ is_null($header->cancellation) ? '-' : date_format_report($header->cancellation->created_at) }}</td>
                    @endif
                    @if($format !== 'xls')
                        <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                            @if($type !== 'QTE')
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
                    @endif
                    @if(in_array($type, [ 'ISS', 'BW' ]) && $format !== 'xls')
                        <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                            <div>
                                @if(! is_null($header->file) && $format !== 'xls')
                                    <a href="{{ route('de.attach.file', ['file' => $header->file]) }}" target="_blank"
                                       class="btn btn-default" style="font-size: 11px;">
                                        Certificado Adjunto
                                    </a>
                                @elseif($format !== 'xls')
                                    @if(count(array_intersect(['RNA'], $user_permissions)) > 0)
                                        <div class="bg-warning" style="font-size: 10px; padding: 7px 5px; cursor: default;">
                                            Certificado NO Adjunto
                                        </div>
                                    @else
                                        <input type="file" nv-file-select uploader="uploader"
                                               options="{formData: [{ 'id' : '{{ encode($header->id) }}', code: '{{ $code }}', report: true }]}"
                                               id="file-{{ $header->issue_number }}"
                                               class="input-file"/>
                                        <label for="file-{{ $header->issue_number }}" data-progress="box-progress-{{ $header->issue_number }}">
                                            <span>Escoge un archivo...</span>
                                        </label>
                                        <div class="box-progress box-progress-{{ $header->issue_number }}" style="display: none;">
                                            <label class="validation-valid-label"
                                                   ng-show="success.message">@{{ success.message }}</label>
                                            <label class="validation-error-label"
                                                   ng-show="errors.message">@{{ errors.message }}</label>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.queue[0].progress + '%' }"></div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>

    @if($format !== 'xls')
        <input type="hidden" id="url-upload" value="{{ route('de.upload.certificate', ['rp_id' => $rp_id, 'header_id' => ':header_id']) }}">
    @endif
</div>