<div class="modal-header bg-primary recuadro">
    <div class="panel-heading">
        <h6 class="modal-title">DATOS GENERALES</h6>
    </div>
</div>
<div class="panel panel-body border-top-success small_body" ng-init="freshPayment()"> 
@if(! isset($_GET['idf']) && $header->type === 'Q')
    {!! Form::open(array('route' => [$retailerProduct->companyProduct->product->code.'.update','rp_id' => $rp_id, 'header_id' => $header_id], 
    'method'        => 'put', 
    'class'         => 'form-horizontal', 
    'class'=>'form-horizontal')) !!}
      <div class="col-md-12">
        <div class="col-xs-12 col-md-6">
            @include('partials.fields', ['type'=>'combo','label'=>'Forma de Pago','id'=>'payment_method','required'=>1,'value'=>$header->payment_method, 'array'=>$data['payment_methods']->toArray()])
            @include('partials.fields', ['type'=>'combo','label'=>'Periodicidad','id'=>'period','required'=>1,'value'=>$header->period, 'array'=>$data['periods']->toArray()])
            @include('partials.fields', ['type'=>'text','label'=>'Plazo (meses)','id'=>'term','required'=>1,'value'=>$header->term])
            @include('partials.fields', ['type'=>'text','label'=>'Nro de Cuenta','id'=>'account_number','required'=>0,'value'=>$header->details->first()->account_number])
            
            <div id="hide_expiration_date"><hr />
                @include('partials.fields', ['type'=>'text','label'=>'Tarjeta de Crédito','id'=>'card_number','required'=>1,'value'=>$header->details->first()->card_number])
                @include('partials.fields', ['type'=>'combo2','label'=>'Mes de Expiración','id'=>'expiration_card_month','required'=>1,'value'=>$header->details->first()->expiration_card_month, 'array'=>$data['months']->toArray()])
                @include('partials.fields', ['type'=>'combo','label'=>'Año de Expiración','id'=>'expiration_card_year','required'=>1,'value'=>$header->details->first()->expiration_card_year, 'array'=>$data['years']->toArray()])
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <span style="display: none;">
            @include('partials.fields', ['type'=>'text2','label'=>'Facturar a','id'=>'bill_name','required'=>1,'value'=>$header->details->first()->client->full_name])
            @include('partials.fields', ['type'=>'text2','label'=>'NIT','id'=>'bill_dni','required'=>1,'value'=>$header->details->first()->client->dni])
            </span>
            {{-- <u><strong>Datos Adicionales</strong></u> --}}
            {{-- @include('partials.fields', ['type'=>'radio','label'=>'Certificado Pre-Impreso','id'=>'pre_printed','required'=>0,'value'=>$header->pre_printed, 'array'=>$data['true_false']->toArray()]) --}}

            <div  id="msg_pignorar">
                @include('partials.fields', ['type'=>'text','label'=>'Nro de Caso','id'=>'case_number','required'=>1,'value'=>$header->case_number])
                <div class="alert alert-warning">
                    No se puede pignorar primas de créditos con destino puro de vivienda H0, H1, H2, H3 y H4.
                </div>
            </div>
        </div>
      </div>
      <div class="text-right">
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
    @var $tomador = $header->details->where("holder",1)->first()
    
    <div class="form-horizontal col-md-12 uppercase lead_background">
        <div class="col-xs-12 col-md-6 ">
            @include('partials.fields', ['type'=>'label','label'=>'Forma de Pago','value'=>config('base.payment_methods_vi.'.$header->payment_method)])
            @include('partials.fields', ['type'=>'label','label'=>'Periodicidad','value'=>config('base.periods.'.$header->period)])
            @include('partials.fields', ['type'=>'label','label'=>'Plazo (meses)','value'=>$header->term])
            @include('partials.fields', ['type'=>'label','label'=>'Nro de Cuenta','value'=>$tomador->account_number])
            <hr />


            @if(($header->payment_method=='TJ') && $tomador->card_number != '')
                @var $card = explode('-', $tomador->card_number);
                @include('partials.fields', ['type'=>'label','label'=>'Tarjeta de Crédito','value'=>$card[0].' - **** - **** - '.$card[1]])
                @include('partials.fields', ['type'=>'label','label'=>'Mes de Expiración','id'=>'expiration_card_month','value'=>monthTranslated($tomador->expiration_card_month)])
                @include('partials.fields', ['type'=>'label','label'=>'Año de Expiración','id'=>'expiration_card_year','value'=>$tomador->expiration_card_year])
            @endif
        </div>
        <div class="col-xs-12 col-md-6">
            {{-- <u><strong>Datos Adicionales</strong></u> --}}
            {{-- @include('partials.fields', ['type'=>'label','label'=>'Certificado Pre-Impreso','value'=>($header->pre_printed)?'SI':'NO']) --}}
            {{-- @include('partials.fields', ['type'=>'label','label'=>'Nro de Pre-Impreso','value'=>$header->pre_printed_number]) --}}

            @if($header->payment_method=='CR')
                @include('partials.fields', ['type'=>'label','label'=>'Nro de Caso del Becoflow','value'=>$header->case_number])
                <div class="alert alert-warning">
                    No se puede pignorar primas de créditos con destino puro de vivienda H0, H1, H2, H3 y H4.
                </div>
            @endif
        </div>
    </div>
@endif
</div>