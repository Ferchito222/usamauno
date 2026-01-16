<div class="modal-header bg-primary recuadro">
    <div class="panel-heading">
        <h6 class="modal-title uppercase">Datos del tomador</h6>
    </div>
</div>
<div class="panel panel-body border-top-success small_body"> 
    <div class="col-xs-12 col-md-6">
        <div class="form-group" ng-init="searchTomador(1)" style="display: {{($header->payment_method=='CO' || $header->payment_method=='TJ')?'none':'block'}}">
            <label class="col-lg-8 control-label ">El tomador es igual al Asegurado : </label>
            <div class="col-lg-4">
                <label class="radio-inline"><input type="radio" name="tomador" ng-click="searchTomador(0)">No</label>
                <label class="radio-inline"><input type="radio" name="tomador" ng-click="searchTomador(1)" checked>Si</label>
            </div>
        </div>
        @if($header->payment_method=='CO' || $header->payment_method=='TJ')
            <strong>El Tomador es igual al asegurado.</strong>
        @endif
    </div>    
    <div class="clearfix"></div>
    <div class="col-xs-12 col-md-8 col-md-offset-2">
        {!! Form::open(['route' => [$retailerProduct->companyProduct->product->code.'.store.tomador', 'rp_id' => $rp_id, 'header_id'=>$header_id], 
        'method' => 'post', 'class' => 'form-horizontal', 'id'=>'search_tomador']) !!}
            <div class="form-group has-feedback lead_background">
                <div class="col-lg-4 col-md-5 col-xs-12">
                        {!! Form::text('s_dni', old('s_dni'), [
                        'class'        => 'form-control',
                        'placeholder'  => 'ingrese el CI'
                        ]) !!}
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                        {!! SelectField::input('s_extension', $data['cities']['CI']->toArray(), [
                            'class' => 'form-control',
                            'style' => 'width: 150px; font-size: 90%;'
                        ], old('s_extension')) !!}
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12">
                    {!! Form::button('Buscar <i class="icon-search4"></i>', ['type' => 'submit', 'class' => 'btn btn-default']) !!}
                </div>
            </div>
        <div class="clearfix"><hr /></div>
        {!! Form::close() !!}
    </div>
    
    <div class="col-md-12">
        <table class="table small_body alert alert-primary">
            <tr>
                <th><strong>CI / NIT</strong></th>
                <th><strong>Nombres y Apellidos</strong></th>
                <th><strong>Fecha Nacimiento</strong></th>
            </tr>
            @foreach($header->details->where("holder",1) as $key => $detail)
                <tr>
                    <td>
                        {{ $detail->client->dni
                                . (! empty($detail->client->complement) ? '-' . $detail->client->complement : '') }}
                            {{ $detail->client->extension }}
                    </td>
                    <td>{{ $detail->client->full_name }} {{ $detail->client->business_name }}</td>
                    <td>{{ dateToFormat($detail->client->birthdate) }}</td>
                </tr>
            @endforeach
        </table>
        <hr />
    </div>
</div>