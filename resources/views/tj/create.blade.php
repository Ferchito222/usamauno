@extends('body')
@var $step=1
@section('body')
    <div class="col-xs-12">
        <div class="col-md-10 col-md-offset-1">
            
            {!! Form::open(['route' => ['client.search', 'rp_id' => $rp_id], 'method' => 'get', 'class' => 'form-horizontal']) !!}
            <div class="form-group has-success">
                <label class="control-label col-lg-3 text-semibold" style="text-align: right;">Busqueda de datos:</label>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-search4"></i></span>
                        {!! Form::text('s_dni', old('s_dni'), [
                            'class'        => 'form-control',
                            'placeholder'  => 'Ingrese Id de Cliente',
                            'autocomplete' => 'off'
                        ]) !!}
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        {!! SelectField::input('s_card', $data['account_number_types']->toArray(), [
                                        'class' => 'form-control',
                                        'style' => 'width: 150px; font-size: 90%;'
                                    ], old('s_card')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    {!! Form::button('Buscar <i class="icon-search4"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                </div>
            </div>
            {!! Form::close() !!}
            
            @if(old('type', $client->type) == null)
                <br />
                <p class="content-group">
                    Para generar el formulario, favor de ingresar el <code>código</code>  de cliente y el tipo de <code>tarjeta</code>.
                </p>
            @endif
        </div>
    </div>
    @if($client->type != null || old('type', $client->type) != null)
        {!! Form::open(['route' => [$retailerProduct->companyProduct->product->code.'.store', 'rp_id' => $rp_id],
        'method'        => 'post',
        'class'         => 'form-horizontal'
        ]) !!}
            <legend class="text-bold"><i class="icon-user-check position-left"></i> Datos del Cliente</legend>
            @include('client.'.$retailerProduct->companyProduct->product->code.'.partials.inputs-quote', ['form' => 'create'])
            
            <div class="col-xs-12 col-md-6">
                <legend class="text-bold">Datos del seguro: {{config('base.account_number_types.'.old('card',$client->type_card))}}</legend>
                @include('partials.fields', ['type'=>'hidden','id'=>'card', 'value'=>$client->type_card])
                
                @if(old('card',$client->type_card) == 'DC')
                    @if(old('debit_cards',$client->debit_cards) == '')
                        <div class="alert alert-warning">El cliente no cuenta con una tarjeta de débito registrada en el sistema "NetBank", favor su verificación.</div>
                    @endif
                    @include('partials.fields', ['type'=>'combo2','label'=>'Nro de Tarjeta','id'=>'card_number', 'required'=>1,'value'=>$client->card_number,'array'=>cards(old('debit_cards',$client->debit_cards))])
                @endif
                
                @if(old('card',$client->type_card) == 'CC')
                    @if(old('credit_cards',$client->credit_cards) == '[]')
                        <div class="alert alert-warning">El cliente no cuenta con una tarjeta de crédito registrada en el sistema "NetBank", favor su verificación.</div>
                    @endif
                    @include('partials.fields', ['type'=>'combo2','label'=>'Nro de Tarjeta','id'=>'card_number', 'required'=>1,'value'=>$client->card_number,'array'=>cards(old('credit_cards',$client->credit_cards))])
                @endif
                
                @include('partials.fields', ['type'=>'hidden','id'=>'debit_cards', 'value'=>$client->debit_cards])
                @include('partials.fields', ['type'=>'hidden','id'=>'credit_cards', 'value'=>$client->credit_cards])
                
                @include('partials.fields', ['type'=>'hidden','id'=>'accounts', 'value'=>base64_encode($client->accounts)])
            </div>  
            <div class="col-xs-12 col-md-12">
                <div class="col-md-12 text-right">
                    {!! Form::button('Cotiza tu mejor seguro <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    @endif  
@endsection
