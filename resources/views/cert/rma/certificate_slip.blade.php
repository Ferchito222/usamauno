<div style="width: 710px; font-weight: normal; font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000000; border: 0px solid #FFFF00;">
        <div style="margin: 8mm 8mm 8mm 8mm;">
            <h3 style="text-align: center">SLIP DE COTIZACIÓN</h3>
            <strong>N° de Cotización: {{ $header->quote_number }}</strong><br /><br />
            <strong><u>Datos del Titular:</u></strong><br />
            <div style="padding-left: 10px;">
                @if($header->client->type == 'N')
                    <strong>Nombre Completo: </strong>{{$header->client->full_name}}<br />
                    <strong>Documento de Identidad: </strong>{{$header->client->dni}} {{ $header->client->complement }} {{$header->client->extension}}<br />
                    <strong>Teléfono: </strong>{{$header->client->phone_number_home}}<br />
                    <strong>Dirección: </strong>{{$header->client->home_address}}<br />
                @else
                    <strong>Nombre o Razón Social: </strong>{{$header->client->full_name}}<br />
                    <strong>NIT: </strong>{{$header->client->dni}}<br />
                    <strong>Teléfono: </strong>{{$header->client->phone_number_home}}<br />
                    <strong>Dirección: </strong>{{$header->client->home_address}}<br />
                @endif
            </div>
            <br />
            <strong><u>Datos de Solicitud:</u></strong><br />
            <div style="padding-left: 10px;">
                <strong>Compañía de Seguros: </strong>{{ $header->retailerProduct->companyProduct->company->name }}<br />
                <strong>Periodo de Contratación: </strong>{{$header->term }} {{config('base.term_types')[$header->type_term]}}<br />
                
                <strong>Inicio de Vigencia: </strong>{{date('d-m-Y',strtotime($header->validity_start))}}<br />
                <strong>Fin de Vigencia: </strong>{{date('d-m-Y',strtotime($header->validity_end))}}<br />
            </div>
            <br />
            <strong><u>Datos del Riesgo:</u></strong><br />
            @foreach($header->details as $value)
                <div style="padding-left: 10px;">
                    <strong>Valor asegurado: </strong> {{ $value->insured_value }}<br />
                </div>
                <br />
            @endforeach
            <br />
        </div>
</div>