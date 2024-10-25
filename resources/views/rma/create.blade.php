@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    @include('partials.fields', ['type' => 'menu-header', 'product' => 'rma'])
@endsection

@section('content')
    <div class="col-md-12">
        <div class="panel panel-flat border-top-primary">
            @include('partials.step', ['step' => '1', 'product' => 'rma'])
            @include('partials.messages')
            <div class="panel-body ">
                <div class="col-xs-12">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="alert alert-danger no-border">
                            <strong>NOTA IMPORTANTE:</strong> Para garantías Múltiples por favor contactarse con 
                            Omar Gonzales del Banco Fortaleza, el sistema no procesara este tipo de garantías por el momento
                        </div>
                        {!! Form::open(['route' => ['client.search', 'rp_id' => $rp_id], 'method' => 'get', 'class' => 'form-horizontal']) !!}
                        <div class="form-group has-success">
                            <label class="control-label col-lg-4 text-semibold" style="text-align: right;">Busqueda de
                                datos:</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-search4"></i></span>
                                    {!! Form::text('s_dni', old('s_dni'), [
                                        'class'        => 'form-control',
                                        'placeholder'  => 'Ingrese Id de Cliente',
                                        'autocomplete' => 'off'
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                {!! Form::button('Buscar <i class="icon-search4"></i>', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @if($client->type != null || old('type', $client->type) != null)
                    {!! Form::open(['route' => ['rma.store', 'rp_id' => $rp_id],
                        'method'        => 'post',
                        'class'         => 'form-horizontal'
                    ]) !!}
                    
                    <h2>Datos del Cliente</h2>
                    @include('client.rma.partials.inputs-quote', ['form' => 'create'])
                    
                    <div class="clearfix"></div><hr/>
                    
                    <h2>Datos del Seguro Solicitado</h2>
                    <div class="col-xs-12 col-md-6">
                        @var $help_vigencia = 'Seleccione Inicio de Vigencia apartir de <b>'.date('d/m/Y').'</b>'
                        @include('partials.fields', ['type'=>'date','label'=>'Inicio de Vigencia','id'=>'validity_start',
                        'required'=>1,'value'=>old('validity_start'), 'help'=>$help_vigencia])    
                        @if($errors->first('validity_start_five') != '')
                            <label id="location-error" class="validation-error-label"
                                   for="location">
                                {!! $help_vigencia !!}
                            </label>
                        @endif
                        <div class="form-group">
                            <label class="col-lg-3 control-label label_required">Plazo del Crédito: </label>
                            <div class="col-lg-3">
                                @include('partials.fields', ['type'=>'text','id'=>'term','value'=>old('term')])
                            </div>
                            <div class="col-lg-6">
                                @include('partials.fields', ['type'=>'combo2','id'=>'type_term','value'=>old('type_term'),'array'=>$data['term_types']->toArray()])
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        @include('partials.fields', ['type'=>'combo2','label'=>'Capitál',       'id'=>'capital',        'required'=>1,'value'=>'C','array'=>$data['capital']->toArray()])
                        <div class="text-right">
                            {!! Form::button('Cotiza tu mejor seguro <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
@endsection