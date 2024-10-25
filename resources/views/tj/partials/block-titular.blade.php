<legend class="text-bold"><i class="icon-user-check position-left"></i> DATOS DEL TITULAR</legend>
<div class="clearfix">&nbsp;</div>
    <table class="table small" style="width: 100%;">
        <thead>
            <tr style="background: #f3f3f3 none repeat scroll 0 0;text-transform: uppercase;">
                <th>C.I.</th>
                <th>Nombres y Apellidos</th>
                <th>Tipo de tarjeta</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>    
            @foreach($header->details as $key => $detail)
                <tr style="text-transform: uppercase;">
                    <td style="background: #f3f3f3 none repeat scroll 0 0;">
                        {{ $detail->client->full_dni }}
                    </td>
                    <td>{{ $detail->client->full_name }}</td>
                    <td>{{ config('base.account_number_types.'.$detail->card) }}</td> 
                    <td>
                        @if($header->type === 'Q')
                            <a href="{{ route($retailerProduct->companyProduct->product->code.'.detail.i.edit', [
                                'rp_id'     => $rp_id,
                                'header_id' => $header_id,
                                'detail_id' => encode($detail->id),
                            ]) }}">
                                @if($detail->completed)
                                    <span class="label label-success">Completado</span>
                                @else
                                    <span class="label label-danger">Pendiente</span>
                                @endif
                            </a>
                        @else
                            <span class="label label-success">Completado</span>
                        @endif
                    </td>
                    <td>
                        @if($header->type === 'Q')
                            <a href="{{ route($retailerProduct->companyProduct->product->code.'.detail.i.edit', [
                                'rp_id'     => $rp_id,
                                'header_id' => $header_id,
                                'detail_id' => encode($detail->id),
                                ]) }}" title="Editar">
                                Editar
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table><br />
