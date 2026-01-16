@extends('body')
@var $step=3
@section('body')
    <div class="col-md-10 col-md-offset-1">
        @if ($header->type === 'I')
            <div class="page-header" style="padding: 5px;">
                <h2>Póliza {{ $header->policy_number}}</h2>
            </div>
        @endif
        <!--Titular-->
        @include($retailerProduct->companyProduct->product->code.'.partials.block-titular' )
    </div>
    <div class="col-md-10 col-md-offset-1">
        <!--Beneficiary-->
        @include($retailerProduct->companyProduct->product->code.'.partials.block-beneficiaries' )
    </div>

    <span ng-controller="HeaderVdController">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <!--formulario cotizacion (Q) -->
            @include($retailerProduct->companyProduct->product->code.'.partials.block-generals' )
        </div>

        @if($header->type === 'I' && $header->issued==false)
            <!-- <div class="col-xs-12 col-md-10 col-md-offset-1"> -->
                <!--formulario Tomador -->
                <!-- @include($retailerProduct->companyProduct->product->code.'.partials.block-tomador' )
            </div> -->

            @if($header->payment_method == 'CO')
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-xs-12 col-md-4" style="border: 1px solid #ff0000; background: #fdffa1; text-align: center;">
                        <b>ORDEN DE COBRO EN CAJA</b>
                        <hr>
                        <div style="text-align: justify;">
                            <b>Póliza:</b> {{$header->policy_number}} <br>
                            <b>Cliente:</b> {{$header->bill_name}} <br>
                            <b>Importe:</b> {{$header->total_premium}} <br>
                            <b>Moneda:</b> Bolivianos
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
            @endif
            <div class="clearfix">&nbsp;</div>
            
            <div class="col-xs-12 col-md-10 col-md-offset-1 text-right">
                <a href="{{ route('home', [])}}" class="btn btn-info" >
                    Guardar y Cerrar <i class="icon-floppy-disk position-right"></i>
                </a>
                <a href="{{ route($retailerProduct->companyProduct->product->code.'.issue', ['rp_id' => $rp_id, 'header_id' => $header_id])}}" class="btn btn-primary box-issued" >
                    Emitir <i class="icon-play position-right"></i>
                </a>
            </div>
        @endif
    </span>
@endsection
