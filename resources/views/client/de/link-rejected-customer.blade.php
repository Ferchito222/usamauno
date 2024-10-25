
    <div class="col-md-offset-2 col-md-8">
        <div class="panel panel-body border-top-primary text-center" style="text-transform: uppercase;">
            El Cliente no cumple con el rango de edades
            <table class="table small" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <th width="40%">Cliente</th>
                    <th width="30%">Edad</th>
                    <th width="30%"></th>
                </tr>
            
                @foreach ($header->rejetedCustomers as $key => $value)
                    <tr>
                        <td width="40%">{{ $value->full_name }}</td>
                        <td width="30%">{{ $value->age }}</td>
                        <td width="30%">
                            <a href="{{ route('template.pdf.show', [
                                'code'      => 'de',
                                'rp_id'     => $rp_id,
                                'type'      => 'rejected_customer',
                                'id'        => encode($value->id),
                                'format'    => 'pdf',
                            ]) }}" target="_blank" class="btn border-slate text-slate-800 btn-flat">
                        <i class="fa fa-file-pdf-o"></i> Emitir nota de rechazo     
                        </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="clearfix">&nbsp;</div>
