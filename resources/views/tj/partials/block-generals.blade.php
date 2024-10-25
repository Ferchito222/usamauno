<div class="clearfix">&nbsp;</div>
<legend class="text-bold"><i class="icon-file-text2 position-left"></i> DATOS GENERALES - {{config('base.account_number_types.'.$header->details->first()->card)}}</legend>
<div class="clearfix">&nbsp;</div>
@if(! isset($_GET['idf']) && $header->type === 'Q')

    {!! Form::open(array('route' => [$retailerProduct->companyProduct->product->code.'.update','rp_id' => $rp_id, 'header_id' => $header_id], 
    'method'        => 'put', 
    'class'         => 'form-horizontal', 
    'class'=>'form-horizontal')) !!}
        @include('partials.fields', ['type'=>'hidden','id'=>'card','value'=>base64_encode($header->details->first()->card)])
      <div class="col-md-12">
            <div class="col-xs-12 col-md-6">
                @include('partials.fields', ['type'=>'combo2v','label'=>'Número de Póliza','id'=>'policy_number','required'=>1,'value'=>$header->policy_number, 'array'=>$data['policies']->toArray()])

                @if($header->details->first()->card=='CC')
                    @include('partials.fields', ['type'=>'textv','label'=>'Nro. de Cuenta','id'=>'account_number','required'=>1,'value'=>$header->details->first()->account_number, 'array'=>$data['accounts']])
                    @include('partials.fields', ['type'=>'label','label'=>'Nro. de Tarjeta', 'value'=>$header->details->first()->card_number])
                @endif

                @if($header->details->first()->card=='DC')
                    @include('partials.fields', ['type'=>'combo2v','label'=>'Caja de Ahorro','id'=>'savings_bank','required'=>1,'value'=>$header->details->first()->savings_bank, 'array'=>$data['accounts']])
                    @include('partials.fields', ['type'=>'label','label'=>'Nro. de Tarjeta','value'=>$header->details->first()->card_number])
                @endif

            </div>
            <div class="col-xs-12 col-md-6">
                @include('partials.fields', ['type'=>'textv','label'=>'Facturar a','id'=>'bill_name','required'=>1,'value'=>$header->details->first()->client->full_name])
                @include('partials.fields', ['type'=>'textv','label'=>'NIT','id'=>'bill_dni','required'=>1,'value'=>$header->details->first()->client->dni])
            </div>
      </div>
      <div class="text-right col-xs-12 col-md-12">
          <legend class="text-bold"></legend>
          @if(checkBeneficiaries($header->details->first()) && $header->details->first()->completed)
            <button type="submit" class="btn btn-primary">Guardar <i
                    class="glyphicon glyphicon-floppy-disk position-right"></i>
            </button>
          @else
            <span class="alert alert-danger">
                Favor de verificar datos del (Titular / Beneficiario)
            </span>
          @endif
      </div>
  {!!Form::close()!!}
@else
    @foreach($header->details as $key => $detail)
        <div class="col-md-12" style="text-transform: uppercase;">
            <div class="col-xs-12 col-md-6">
                @include('partials.fields', ['type'=>'label','label'=>'Número de Póliza','value'=>$header->policy_number])
                @if($detail->card=='CC')
                    @include('partials.fields', ['type'=>'label','label'=>'Nro. de Cuenta','value'=>$detail->account_number])
                    @include('partials.fields', ['type'=>'label','label'=>'Nro. de Tarjeta','value'=>$detail->card_number])
                @endif
                
                @if($detail->card=='DC')
                    @include('partials.fields', ['type'=>'label','label'=>'Caja de Ahorro','value'=>$detail->savings_bank])
                    @include('partials.fields', ['type'=>'label','label'=>'Nro. de Tarjeta','value'=>$detail->card_number])
                @endif
            </div>
            <div class="col-xs-12 col-md-6">
                @include('partials.fields', ['type'=>'label','label'=>'Facturar a','value'=>$detail->client->full_name])
                @include('partials.fields', ['type'=>'label','label'=>'NIT','value'=>$detail->client->dni])
            </div>
        </div>
    @endforeach
@endif