@extends('body')
@var $step=2
@section('body')
    <div class="panel-body ">
        <div class="col-xs-12 col-md-12">
            <div class="col-md-4">
                <div class="panel panel-body border-top-primary text-center">
                    <div class="form-group">
                        {!! Html::image($retailerProduct->companyProduct->company->image, null, ['style' => 'max-height: 130px;']) !!}
                    </div>
                    <h6 class="no-margin text-semibold">Prima Total:</h6>
                    <p class="text-muted content-group-sm">
                            {{$header->total_premium}} {{$header->currency}}  Mensual
                        <br>
                    </p>
                    <hr>
                    <a href="{{ route($retailerProduct->companyProduct->product->code.'.edit', ['rp_id' => $rp_id, 'header_id' => $header_id]) }}"
                       class="btn btn-primary"><i class="icon-arrow-right14 position-left"></i>
                        Emitir
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
