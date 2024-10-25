<h3>Cobranzas no actualizadas en abrenet</h3>
<table border="1" class="table-bordered datatable-basic small" style="color:#A5A5A5; width: 100%; text-transform: uppercase; font-size: 9px" width="100%" >
        <tr style="background: #337EB5; color: white;" class="capital_letters small">
            <th>NroCertificado</th>
            <th>NroCuentaTJ</th>
            <th>CodigoCliente</th>
            <th>NombreCliente</th>
            <th>Importe</th>
            <th>NroCuenta</th>
            <th>MonedaCuenta</th>
            <th>NroTransaccion</th>
            <th>FechaTransaccion</th>
            <th>ImporteDebitado</th>
            <th>Detalle del error</th>
        </tr>
        @foreach($array as $key => $collection)
            <tr>
                <td>{{isset($collection['NroCertificado'])?$collection['NroCertificado']:''}}</td>
                <td>{{isset($collection['NroCuentaTJ'])?$collection['NroCuentaTJ']:''}}</td>
                <td>{{isset($collection['CodigoCliente'])?$collection['CodigoCliente']:''}}</td>
                <td>{{isset($collection['NombreCliente'])?$collection['NombreCliente']:''}}</td>
                <td>{{isset($collection['Importe'])?$collection['Importe']:''}}</td>
                <td>{{isset($collection['NroCuenta'])?$collection['NroCuenta']:''}}</td>
                <td>{{isset($collection['MonedaCuenta'])?$collection['MonedaCuenta']:''}}</td>
                <td>{{isset($collection['NroTransaccion'])?$collection['NroTransaccion']:''}}</td>
                <td>{{isset($collection['FechaTransaccion'])?$collection['FechaTransaccion']:''}}</td>
                <td>{{isset($collection['ImporteDebitado'])?$collection['ImporteDebitado']:''}}</td>
                <td>{{isset($collection['error'])?$collection['error']:''}}</td>
            </tr>
        @endforeach
    </table>