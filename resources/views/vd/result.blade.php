@extends('body')
@var $step=2
@section('body')
    <div class="panel-body ">
        <div class="col-xs-12 col-md-12">
            <div class="col-md-4">
                <div class="panel panel-body border-top-primary text-center">
                    <div class="form-group">
                        {!! Html::image($retailerProduct->companyProduct->company->image, null, ['style' => 'height: 100px;']) !!}
                    </div>
                    {{-- <h6 class="no-margin text-semibold">{{$header->plan->name}} </h6> --}}
                    <p class="text-muted content-group-sm">
                        {{-- <strong>Prima: Bs {{ number_format((isset($header->plan->annual_premium) ? $header->plan->annual_premium : '156'), 2, '.', '')}}  Anual o Bs {{ number_format((isset($header->plan->monthly_premium) ? $header->plan->monthly_premium : (150 / 12)), 2, '.', '')}}  Mensual</strong> --}}
                        {{-- <strong>Prima: Bs {{ number_format(($header->plan->annual_premium), 2, '.', '')}}  Anual o Bs {{ number_format(($header->plan->monthly_premium), 2, '.', '')}}  Mensual</strong> --}}
                        <strong>Prima: Bs {{ number_format(($header->plan->annual_premium), 2, '.', '')}}  Anual</strong>
                        <br>
                    </p>
                    <hr>
                    @var $client = $header->details->first()
                    @var $responses = json_decode($client->response->response)
                    @var $val = 0
                    
                    @foreach($responses as $key => $response)
                    
                        @if(($response->id == 24) && $response->expected != $response->response)                        
                            @var $val = 1
                        @endif
                    @endforeach

                    @if($val == 0)
                        <a href="{{ route($retailerProduct->companyProduct->product->code.'.edit', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                        class="btn btn-primary"><i class="icon-arrow-right14 position-left"></i>
                            Emitir
                        </a>
                    @else
                        <div class="col-xs-12 col-md-12 text-center">
                            <span class="alert alert-danger" style="font-size: 6pt;">Las respuestas del cuestionario no son las esperadas.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
