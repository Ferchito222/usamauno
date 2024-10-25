@if($format === 'xls')
    <meta charset="utf-8">
@else
    @var $user_profile = $user->profile->first()->slug;
    @var $user_permissions = $user->permissions->pluck('slug')->toArray();
@endif

<div style="{{ $format !== 'xls' ? 'width: 100%;' : '' }} overflow: scroll;" ng-controller="ReportController" ng-init="uploadFile();">
    <table class="table-bordered table-hover gridview small" border="1" {{ $format !== 'xls' ? 'width=230%' : '' }} style="text-transform: uppercase;">
        <thead>
        <tr style="font-weight: bold;">
            <th>NRO. DE <br />CERTIFICADO</th>
            <th>NOMBRE <br />CLIENTE</th>
            <th>AP. PATERNO</th>
            <th>AP. MATERNO</th>
            <th>CODIGO<br />CLIENTE</th>
            <th style="width: 100px;">C.I.</th>
            <th style="width: 100px;">EXTENSION</th>
            <th style="width: 100px;">CORREO ELECTRONICO</th>
            <th style="width: 100px;">FECHA DE NACIMIENTO</th>
            <th>TIPO DE TARJETA</th>
            <th>NRO DE TARJETA</th>
            <th>NRO DE CUENTA</th>
            <th>CAJA DE AHORRO</th>
            <th>USUARIO</th>
            <th>SUCURSAL / AGENCIA</th>
            <th>NRO DE CUOTA</th>
            <th>FECHA DE CUOTA</th>
            <th>MONTO (BS)</th>
            <th>COBRADO</th>
            <th>NRO DE <br />TRANSACCION</th>
            <th>FECHA DE <br />TRANSACCION</th>
            <th>MONTO DE <br />TRANSACCION (BS)</th>
            <th>MONTO DE <br />TRANSACCION (USD)</th>
            <th>TIPO DE CAMBIO<br />TRANSACCION (BS)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($collections as $key => $collection)
                <tr>
                    <td style="font-weight: bold;">{{$collection->header->prefix}} - {{$collection->header->issue_number}}</td>
                    <td>{{$collection->header->details->first()->client->first_name}}</td>
                    <td>{{$collection->header->details->first()->client->last_name}}</td>
                    <td>{{$collection->header->details->first()->client->mother_last_name}}</td>
                    <td>{{$collection->header->details->first()->client->code}}</td>
                    <td>{{$collection->header->details->first()->client->dni}}</td>
                    <td>{{$collection->header->details->first()->client->extension}}</td>
                    <td style="text-transform: lowercase">{{$collection->header->details->first()->client->email}}</td>
                    <td>{{ date('d/m/Y', strtotime($collection->header->details->first()->client->birthdate)) }}</td>
                    <td>{{config('base.account_number_types.'.$collection->header->details->first()->card)}}</td>
                    <td>{{$collection->header->details->first()->card_number}}</td>
                    <td>{{$collection->header->details->first()->account_number}}</td>
                    <td>{{$collection->header->details->first()->savings_bank}}</td>
                    <td>{{ $collection->header->user->full_name }}</td>
                    <td>
                        @if($collection->header->retailerCityAgency)
                            {{ $collection->header->retailerCityAgency->retailerCity->city->name }} / {{ $collection->header->retailerCityAgency->agency->name }}
                        @else
                            {{ $collection->header->placeIssuance instanceof \Sibas\Entities\City ? $collection->header->placeIssuance->name : $collection->header->user->city->name }}
                            /
                            {{ $collection->header->user->agency->name }}
                        @endif
                    </td>
                    <td>{{$collection->fee_number}}</td>
                    <td>{{date('d/m/Y',strtotime($collection->fee_date))}}</td>
                    <td>{{$collection->fee_amount}}</td>
                    <td>{{$collection->charged?'Si':'No'}}</td>
                    <td>{{$collection->transaction_number?$collection->transaction_number:'---'}}</td>
                    <td>{{$collection->transaction_date?date('d/m/Y',strtotime($collection->transaction_date)):''}}</td>
                    <td>{{$collection->transaction_amount?$collection->transaction_amount:'0,0'}}</td>
                    <td>{{$collection->transaction_amount_usd?$collection->transaction_amount_usd:''}}</td>
                    <td>{{$collection->exchange_rate?$collection->exchange_rate:''}}</td>
                </tr>
        @endforeach
        </tbody>
    </table>
</div>