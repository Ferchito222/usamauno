@if($format === 'xls')
    <meta charset="utf-8">
@else
    @var $user_profile = $user->profile->first()->slug;
    @var $user_permissions = $user->permissions->pluck('slug')->toArray();
@endif

<div style="{{ $format !== 'xls' ? 'width: 100%;' : '' }} overflow: scroll;" ng-controller="ReportController"
    ng-init="uploadFile();">
    <table class="table-bordered table-hover gridview" border="1" {{ $format !== 'xls' ? 'width=200%' : '' }}
        style="font-size: 65%;">
        <thead>
            <tr style="font-weight: bold;">
                @if($type === 'QTE')
                    <th>NRO. DE COTIZACIÓN</th>
                @else
                    <th>NRO. DE POLIZA</th>
                    <th>TIPO</th>
                    <th>ESTADO</th>
                @endif
                <th>CLIENTE</th>
                <th style="width: 100px;">C.I.</th>
                <th>FECHA NACIMIENTO</th>
                <th>CODIGO CLIENTE</th>
                <th>CELULAR CLIENTE</th>
                <th>USUARIO</th>
                <th>CARGO</th>
                <th>SUCURSAL</th>
                <th>AGENCIA</th>
                <th>FECHA DE INGRESO</th>
                @if($type !== 'QTE')
                    <th>CLIENTE TOMADOR</th>
                    <th>PRIMA TOTAL</th>
                    <th>FORMA DE PAGO</th>
                    <th>PERIODICIDAD</th>
                    <th>PLAZO (MESES)</th>
                    <th>NRO DE CUENTA</th>
                    <th>TARJETA DE CREDITO</th>

                    <th>EMITIDO</th>
                    <th>FECHA DE EMISIÓN</th>
                    <th>ANULADO</th>
                    <th>ANULADO POR</th>
                    <th>MOTIVO DE ANULACIÓN</th>
                    <th>FECHA DE ANULACIÓN</th>
                @endif
                @if($format !== 'xls')
                    <th></th>
                    <th>Certificado Adjunto</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($headers as $header)
                @var $rows = 1

                @foreach($header->details as $index => $detail)
                    @if($index == 0)
                        @var $c = $header->renovation_number
                        @for($i = 0; $i <= $header->renovation_number; $i++)

                            @var $t = ($header->renovation_number - $i) * ($header->term / 12)
                            @var $policy_number = policy_number($header->policy_number, $i, $header->prefix);
                            @var $f_emision = policy_issue_date($t, $header->date_issue, $header->prefix, 'year')
                            @var $date_end = date ('Y-m-d H:m:i',strtotime ('+'.$header->term.' month' , strtotime($f_emision)))
                            @var $cancel = $header->cancellation ? $header->cancellation : ''
                            @var $status = policy_status ($header->canceled, $date_end, $cancel)
                            @var $type_policy = policy_type ($i)
                            @var $cancel = cancel_info($date_end, $cancel)
                            @var $married_name = ''
                            @if(!empty($detail->client->married_name))
                                @if($detail->client->civil_status == 'C')
                                    @var $married_name = ' de '.$detail->client->married_name
                                @elseif($detail->client->civil_status == 'V')
                                    @var $married_name = ' Vda de '.$detail->client->married_name
                                @endif
                            @endif

                            <tr>
                                @if($type === 'QTE')
                                    <td style="font-weight: bold;">{{ $header->quote_number }}</td>
                                @else
                                    <td>{{ $policy_number }}</td>
                                    <td>{{ $type_policy }}</td>
                                    <td>{{ $status }}</td>
                                @endif
                                <td>{{ $detail->client->full_name . $married_name }}
                                </td>
                                <td>
                                    {{ $detail->client->dni
                                    . (!empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                                    {{ $detail->client->extension }}
                                </td>
                                <td>{{ dateToFormat($detail->client->birthdate) }}</td>

                                <td>{{ $detail->client->code }}</td>
                                <td>{{ $detail->client->phone_number_home }}</td>
                                <td>{{ $header->user->full_name }}</td>
                                <td>{{ $header->user->position }}</td>
                                <td>{{ $header->placeIssuance instanceof \Sibas\Entities\City ? $header->placeIssuance->name : $header->user->city->name }}
                                </td>
                                <td>
                                    {{ $header->user->agency->name }}
                                </td>
                                <td>{{ date_format_report($header->created_at) }}</td>
                                @if($type !== 'QTE')
                                    <td>{{ $detail->holder == 1 ? 'SI' : 'NO' }}</td>
                                    <td>{{ $header->total_premium }} BS</td>
                                    <td>{{ config('base.payment_methods_vi.' . $header->payment_method) }}</td>
                                    <td>{{ config('base.periods.' . $header->period) }}</td>
                                    <td>{{ $header->term }}</td>
                                    <td>{{ $detail->account_number }}</td>
                                    @if($detail->card_number && $detail->card_number = !'-')
                                        <td>{!!format_credit_card($detail->card_number)!!}</td>
                                    @else
                                        <td>&nbsp;</td>
                                    @endif
                                    <td>{{ $header->issued ? 'SI' : 'NO' }}</td>
                                    <td>{{ $header->issued ? date_format_report($f_emision) : '-' }}</td>
                                    <td>{{ $cancel['anulado'] }}</td>
                                    <td>{{ $cancel['anulado_por'] }}</td>
                                    <td>{{ $cancel['motivo'] }}</td>
                                    <td>{{ $cancel['fecha_anulado'] }}</td>
                                @endif
                                @if($format !== 'xls')
                                    <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                                        @if($type !== 'QTE' && $header->issued == true)
                                            <a href="{{ route('certificate.show', [
                                                    'code' => $header->retailerProduct->companyProduct->product->code,
                                                    'rp_id' => $rp_id,
                                                    'type' => 'certificate',
                                                    'header_id' => encode($header->id),
                                                    'format' => 'pdf',
                                                ]) }}" target="_blank" class="btn btn-info" style="font-size: 90%;">
                                                Ver Certificado de Emisión
                                            </a>
                                        @endif
                                    </td>
                                    @if($type === 'ISS')
                                        <td rowspan="{{ $rows }}" style="vertical-align: middle; {{ $index > 0 ? 'display: none;' : '' }}">
                                            @if($header->file)
                                                <a href="{{ route('certificate.dattached', [
                                                            'code' => $header->retailerProduct->companyProduct->product->code,
                                                            'rp_id' => $rp_id,
                                                            'file' => $header->file,
                                                        ]) }}" class="btn btn-danger" style="font-size: 90%;">
                                                    Descargar Certificado
                                                </a>
                                            @else
                                                {!! Form::open([
                                                            'route' => [
                                                                'certificate.attached',
                                                                'code' => $header->retailerProduct->companyProduct->product->code,
                                                                'type' => 'certificate',
                                                                'header_id' => encode($header->id),
                                                                'rp_id' => $rp_id
                                                            ],
                                                            'method' => 'post',
                                                            'files' => true,
                                                            'class' => 'form-horizontal'
                                                        ]) !!}
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-lg-7">
                                                            <input type="file" class="file-styled" name="attached" id="attached">
                                                        </div>

                                                        <label id="location-error" class="validation-error-label"
                                                            for="location">{{ $errors->first('txt_client') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-6">
                                                    <div class="form-group">
                                                        {!! Form::button('Adjuntar <i class="icon-upload"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    @endif
                                @endif
                            </tr>
                            @var $c--
                        @endfor
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

    @if($format !== 'xls')
        <input type="hidden" id="url-upload"
            value="{{ route('de.upload.certificate', ['rp_id' => $rp_id, 'header_id' => ':header_id']) }}">
    @endif
</div>