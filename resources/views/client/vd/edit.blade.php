@extends('body')
@var $step=2
@section('body')
    {!! Form::open(['route' => [$retailerProduct->companyProduct->product->code.'.detail.update',  'rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => $detail_id], 'method' => 'put', 'class' => 'form-horizontal']) !!}
        <div class="panel-body ">
            @include('client.'.$retailerProduct->companyProduct->product->code.'.partials.inputs-quote', ['form' => 'create'])
            <div class="text-right">
                <a href="{{ route($retailerProduct->companyProduct->product->code.'.client.list', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                   class="btn border-slate text-slate-800 btn-flat">Cancelar</a>
                {!! Form::button('Actualizar Cliente <i class="icon-arrow-right14 position-right"></i>', [
                    'type' => 'submit',
                    'class' => 'btn btn-primary'])
                !!}
            </div>
        </div>
    {!! Form::close() !!}
@endsection