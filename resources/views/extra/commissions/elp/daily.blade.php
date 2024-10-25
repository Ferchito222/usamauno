@extends('extra.commissions.layout')

@section('content')
    <table style="width: 100%; font-family: helvetica, georgia, serif; font-size:10pt; border-collapse: collapse; border: 2px solid #17A086; text-align: center;"
           border="1">
        <tr style="background: #17A086; color:white; text-align:center;">
            <th></th>
            <th>PRODUCTO</th>
            <th>SUCURSAL</th>
            <th>AGENCIA</th>
            <th>TIPO DE AGENCIA</th>
            <th>NOMBRE VENDEDOR</th>
            <th>CODIGO VENDEDOR</th>
            <th>CARGO VENDEDOR</th>
            <th>FORMA DE PAGO</th>
            <th>REFERIDO POR</th>
            <th>NOMBRE ASEGURADO</th>
            <th>CI ASEGURADO</th>
            <th>SEXO</th>
            <th>ESTADO</th>
            <th>FECHA EMISIÓN</th>
        </tr>

        @var $i  = 1;
        @var $bg = true;

        @foreach($headers as $key => $header)
            <tr style="vertical-align:middle; background: {{ $bg ? '#D0F5EE' : '#fff' }};">
                <td style="font-size:6pt;">{{ $i }}*</td>
                <td>{{ $header->product_text }}</td>
                <td>{{ $header->city_name }}</td>
                <td>{{ $header->agency_name }}</td>
                <td>{{ $header->agency_type }}</td>
                <td>{{ $header->user_full_name }}</td>
                <td>{{ $header->user_code }}</td>
                <td>{{ $header->user_position }}</td>
                <td>{{ config('base.payment_methods.elp.' . $header->payment_method) }}</td>
                <td>{{ $header->reference_full_name }}</td>
                <td>{{ $header->client_full_name }}</td>
                <td>{{ $header->client_dni }} {{ $header->client_extension }}</td>
                <td>{{ config('base.client_genders.' . $header->client_gender) }}</td>
                <td>{{ $header->state_name }}</td>
                <td>{{ $header->date_of_issue }}</td>
            </tr>

            @var $i  = $i + 1;
            @var $bg = $bg ? false : true;
        @endforeach
    </table>
@endsection