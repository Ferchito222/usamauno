<div class="modal-header bg-primary recuadro">
    <div class="panel-heading">
        <h6 class="modal-title" style="text-transform: uppercase;" >Datos del Titular </h6>
    </div>
</div>
<div class="panel panel-body border-top-success">
    <table class="table small_body" style="width: 100%;">
        <thead>
            <tr style="background: #fafafa none repeat scroll 0 0; text-transform: uppercase;">
                <th>C.I.</th>
                <th>Nombres y Apellidos</th>
                <th>Fecha Nacimiento</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>    
            <tr>
                <td style="background: #fafafa none repeat scroll 0 0;">
                    {{ $header->details->first()->client->dni
                            . (! empty($header->details->first()->client->complement) ? '-' . $header->details->first()->client->complement : '') }}
                        {{ $header->details->first()->client->extension }}
                </td>
                <td>{{ $header->details->first()->client->full_name }}</td>
                <td>{{ dateToFormat($header->details->first()->client->birthdate) }}</td>
                <td>
                    @if($header->type === 'Q')
                        <a href="{{ route($retailerProduct->companyProduct->product->code.'.detail.i.edit', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                            'detail_id' => encode($header->details->first()->id),
                        ]) }}">
                            @if($header->details->first()->completed)
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
                            'detail_id' => encode($header->details->first()->id),
                            ]) }}">
                            <i class="icon-pencil3"></i> Editar
                        </a>
                    @endif
                </td>
            </tr>
        </tbody>
    </table><br />
</div>