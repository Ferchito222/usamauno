<div class="clearfix">&nbsp;</div>
<legend class="text-bold"><i class="icon-users4 position-left"></i> BENEFICIARIOS</legend>
@if ($header->details->first()->beneficiaries->count() === 0)
    <p class="content-group">
        Para poder continuar es obligatorio que al menos registre <code>1 beneficiario</code>.
    </p>
@endif
<span ng-controller="DetailDeController as detailDe">
    <div class="col-md-12" style="text-align: right;">
        @if($header->type === 'Q' && count($header->details->first()->beneficiaries->where('coverage','VI')) < 1)
            <a href="{{ route($retailerProduct->companyProduct->product->code.'.beneficiary.create', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                            'detail_id' => encode($header->details->first()->id),
                            'type'      => 'VI'
                        ]) }}"
                           ng-click="detailDe.createBeneficiary($event)" class="btn btn-default" title="Registrar Nuevo">
                Registrar Nuevo <i class="glyphicon glyphicon-plus"></i>
            </a>
        @endif                            
    </div>
    <div class="clearfix">&nbsp;</div>
    <table class="table small" style="width: 100%;text-transform: uppercase;">
        <thead>
            <tr style="background: #f3f3f3 none repeat scroll 0 0;">
                <th>Nombre</th>
                <th>CI</th>
                <th>Parentesco</th>
                <th>Porcentaje</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @var $i=0
            @foreach($header->details->first()->beneficiaries as $key => $value)
                <tr>
                    <td style="background: #f3f3f3 none repeat scroll 0 0;font-weight: bold;">
                        {{$value->first_name}} {{$value->last_name}} {{$value->mother_last_name}}
                    </td>
                    <td>{{$value->dni}} {{$value->extension}}</td>
                    <td>{{$value->relationship}}</td>
                    <td style="text-align: center;" class="alert alert-primary">{{$value->percentage}} %</td>
                    <td>
                        @if($header->type === 'Q')
                            <a href="{{ route($retailerProduct->companyProduct->product->code.'.beneficiary.edit', [
                                'rp_id'     => $rp_id,
                                'header_id' => $header_id,
                                'detail_id' => encode($header->details->first()->id),
                                'beneficiary_id' => encode($value->id),
                                'type'      => $value->coverage
                            ]) }}"
                               ng-click="detailDe.editBeneficiary($event)" title="Editar">
                                Editar 
                            </a> | 
                            <a href="{{ route($retailerProduct->companyProduct->product->code.'.beneficiary.destroy', [
                                    'rp_id'     => $rp_id,
                                    'header_id' => $header_id,
                                    'beneficiary_id' => encode($value->id),
                                ]) }}" ng-click="delete($event)" title="Eliminar">
                                Eliminar 
                            </a>
                        @endif
                    </td>
                </tr>
                @var $i=$i+$value->percentage
            @endforeach
        </tbody>
    </table>
</span>
<div class="help-block">
    Solo puede registrar <strong>1 beneficiario</strong>
</div>