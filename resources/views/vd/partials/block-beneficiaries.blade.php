<div class="modal-header bg-primary recuadro">
    <div class="panel-heading">
        <h6 class="modal-title" style="text-transform: uppercase;">Beneficiarios para {{$retailerProduct->companyProduct->product->name }}</h6>
    </div>
</div>
<div class="panel panel-body border-top-success" ng-controller="DetailDeController as detailDe">
    <div class="col-md-12" style="text-align: right;">
        @if($header->type === 'Q' && count($header->details->first()->beneficiaries) <= 3)
            {{-- {{ dd(strtoupper($retailerProduct->companyProduct->product->code)) }} --}}
            <a href="{{ route($retailerProduct->companyProduct->product->code.'.beneficiary.create', [
                            'rp_id'     => $rp_id,
                            'header_id' => $header_id,
                            'detail_id' => encode($header->details->first()->id),
                            'type'      => strtoupper($retailerProduct->companyProduct->product->code)
                        ]) }}"
                           ng-click="detailDe.createBeneficiary($event)" class="btn btn-default" title="Registrar Nuevo">
                Registrar Nuevo <i class="glyphicon glyphicon-plus"></i>
            </a>
        @endif                            
    </div>
    <div class="clearfix">&nbsp;</div>
    <table class="table small_body" style="width: 100%">
        <thead>
            <tr style="background: #fafafa none repeat scroll 0 0; text-transform: uppercase;">
                <th>Nombre</th>
                <th>Ap. Paterno</th>
                <th>Ap. Materno</th>
                <th>DNI</th>
                <th>Extensión</th>
                <th>Parentesco</th>
                <th>Porcentaje</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @var $i=0
            @foreach($header->details->first()->beneficiaries as $key => $value)
                <tr>
                    <td style="background: #fafafa none repeat scroll 0 0; font-weight: bold;">{{$value->first_name}}</td>
                    <td>{{$value->last_name}}</td>
                    <td>{{$value->mother_last_name}}</td>
                    <td>{{$value->dni}}</td>
                    <td>{{$value->extension}}</td>
                    <td>{{$value->relationship}}</td>
                    <td style="text-align: center;">{{$value->percentage}} %</td>
                    <td>
                        @if($header->type === 'Q')
                            <a href="{{ route($retailerProduct->companyProduct->product->code.'.beneficiary.edit', [
                                'rp_id'     => $rp_id,
                                'header_id' => $header_id,
                                'detail_id' => encode($header->details->first()->id),
                                'beneficiary_id' => encode($value->id),
                                'type'      => strtoupper($retailerProduct->companyProduct->product->code)
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
                <tr>
                    <th colspan="6" style="text-align: right;">La sumatoria de porcentajes debe ser igual al 100 %</th>
                    <th style="text-align: center;" class="{{$i != 100?'alert-danger':'alert-success'}}">{{$i}} %</th>
                    <th><strong>Total</strong></th>
                </tr>
        </tbody>
</table>
@if(checkBeneficiaries($header->details->first()) == false)
    <span style="color: red;">Debe registrar al menos un beneficiario.</span>
@endif
</div>