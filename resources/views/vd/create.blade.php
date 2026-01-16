@extends('body')
@var $step=1
@section('body')

    @include('partials.form-search-client', ['type_form' => 2, 'placeholder' => 'Ingrese CI'])
    {!! Form::open(['route' => [$retailerProduct->companyProduct->product->code.'.store', 'rp_id' => $rp_id],
    'method'        => 'post',
    'class'         => 'form-horizontal'
    ]) !!}
        <h2>Datos del Cliente</h2>

        @include('client.'.$retailerProduct->companyProduct->product->code.'.partials.inputs-quote', ['form' => 'create'])

        @include('partials.fields', ['type'=>'hidden','label'=>'Plan','id'=>'ad_plan_id', 'required'=>1,'value'=>'1','array'=>$data['plans']->toArray()])

        {{-- <div class="col-xs-12 col-md-12"><hr />
            <h2>Datos del Seguro</h2>
            <div class="col-xs-12 col-md-6">
                @include('partials.fields', ['type'=>'combo2','label'=>'Plan','id'=>'ad_plan_id', 'required'=>1,'value'=>'','array'=>$data['plans']->toArray()])
            </div>
        </div> --}}

        <div class="col-xs-12 col-md-12">
            @var $plan_dis = 'font-weight:bold; text-align:center;';
            @foreach($data['plans'] as $clave => $valor)
                @if(!empty($clave))
                    <div class="plan-detail-pr" id="{{$valor['id']}}" style="{{$plan_dis}}">
                        {!! $valor['description'] !!}
                    </div>
                @endif
            @endforeach
        </div>

        <div class="col-xs-12 col-md-12">
            <div class="col-md-12 text-right">
                {!! Form::button('Cotiza tu mejor seguro <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
    <script type="text/javascript">
        $(document).ready(function(){
            $(".plan-detail-pr").hide();
            $("#ad_plan_id").change(function(e){
                var plan = $(this).prop('value');

                $('.plan-detail-pr').hide();

                if (plan !== '') {
                    $('#' + plan).show();
                }
            });

        });
    </script>
@endsection
