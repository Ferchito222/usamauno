@if($format === 'xls')
    <meta charset="utf-8">
@else
    @var $user_profile = $user->profile->first()->slug;
    @var $user_permissions = $user->permissions->pluck('slug')->toArray();
@endif

<div style="{{ $format !== 'xls' ? 'width: 100%;' : '' }} overflow: scroll;" ng-controller="ReportController" ng-init="uploadFile();">
    <table class="table-bordered table-hover gridview" border="1" {{ $format !== 'xls' ? 'width=200%' : '' }} style="font-size: 65%;">
        <thead>
        <tr style="font-weight: bold;">
            <th>
                @if($type === 'QTE')
                    NRO. DE COTIZACIÓN
                @else
                    NRO. DE CERTIFICADO
                @endif
            </th>
            <th>CLIENTE</th>
            <th style="width: 100px;">C.I.</th>
            <th>PRIMA TOTAL</th>
            <th>PLAZO DEL CREDITO /<br>TIPO DEL PLAZO</th>
            <th>TITULAR</th>
            <th>CODIGO CATASTRAL</th>
            <th>TIPO DE MATERIA</th>
            <th>DESCRIPCION</th>
            <th>USO</th>
            <th>CIUDAD</th>
            <th>ZONA</th>
            <th>LOCALIDAD</th>
            <th>DIRECCION</th>
            <th>VALOR ASEGURADO</th>
            <th>USUARIO</th>
            <th>SUCURSAL / AGENCIA</th>
            <th>FECHA DE INGRESO</th>
            @if($type !== 'QTE')
                <th>EMITIDO</th>
                <th>FECHA DE EMISIÓN</th>
                <th>ESTADO COMPAÑIA</th>
                <th>ESTADO BANCO</th>
                <th>TASA FINAL</th>
                <th>NUMERO DE OPERACION</th>
                <th>ANULADO</th>
                <th>ANULADO POR</th>
                <th>MOTIVO DE ANULACIÓN</th>
                <th>FECHA DE ANULACIÓN</th>
            @endif
            @if($format !== 'xls')
                <th></th>
            @endif
            @if(in_array($type, [ 'ISS', 'BW' ]))
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
                    <td>{{ config('base.headlines.D') }}</td>
                    <td>{{ $detail->nro_folio_real }}</td>
                    <td>{{ config('base.property_types.'.$detail->matter_insured) }}</td>
                    <td>{{ $detail->matter_description }}</td>
                    <td>{{ config('base.property_uses.'.$detail->use) }}</td>
                    <td>{{ $detail->city }}</td>
                    <td>{{ $detail->zone }}</td>
                    <td>{{ $detail->locality }}</td>
                    <td>{{ $detail->address }}</td>
                    <td>{{ $detail->insured_value }}</td>
                    <td>{{ $header->user->full_name }}</td>
                    <td>
                        {{ $header->retailerCityAgency->retailerCity->city->name }} / {{ $header->retailerCityAgency->agency->name }}
                    </td>
                    <td>{{ date_format_report($header->created_at) }}</td>
                    @if($type !== 'QTE')
                        <td>{{ $header->issued ? 'SI' : 'NO' }}</td>
                        <td>{{ $header->issued ? date_format_report($header->date_issue) : '-' }}</td>
                        <td>{{ getDeStateCompany($header, $detail) }}</td>
                        <td>{{ $header->issued ? 'Emitido' : 'No Emitido' }}</td>
                        <td>
                            {{ $detail->rate }}
                        </td>
                        <td>{{ $header->operation_number }}</td>
                        <td>{{ $header->canceled ? 'SI' : 'NO' }}</td>
                        <td>{{ is_null($header->cancellation) ? '-' : $header->cancellation->user->full_name }}</td>
                        <td>{{ is_null($header->cancellation) ? '-' : $header->cancellation->reason }}</td>
                        <td>{{ is_null($header->cancellation) ? '-' : $header->cancellation->created_at }}</td>
                    @endif
                    @if($format !== 'xls')
                        <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                            @if($type !== 'QTE')
                                <a href="{{ route('certificate.show', [
                                    'code'      => $code,
                                    'rp_id'     => $rp_id,
                                    'type'      => 'certificate',
                                    'header_id' => encode($header->id),
                                    'format'    => 'pdf',
                                ]) }}" target="_blank" class="btn btn-info" style="font-size: 100%;">
                                    Ver Certificado de Emisión
                                </a>
                            @endif
                        </td>
                    @endif
                    @if(in_array($type, [ 'ISS', 'BW' ]))
                        <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                            <div>
                                @if(! is_null($header->file))
                                    <a href="{{ route(strtolower($header->prefix).'.attach.file', ['file' => $header->file]) }}" target="_blank"
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
        <input type="hidden" id="url-upload" value="{{ route('td.upload.certificate', ['rp_id' => $rp_id, 'header_id' => ':header_id']) }}">
    @endif
</div>