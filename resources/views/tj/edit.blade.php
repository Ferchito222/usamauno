@extends('body')
@var $step=3
@section('body')
    <div class="col-md-10 col-md-offset-1">
        @if ($header->type === 'I')
            <p class="content-group">
                Nueva póliza pre-aprobada con el Nro. <code>{{ $header->prefix }} - {{$header->issue_number}}</code>, 
                para concluir con la emisión por favor presione el botón 
                <code>
                    <a href="{{ route($retailerProduct->companyProduct->product->code.'.issue', ['rp_id' => $rp_id, 'header_id' => $header_id])}}" title="Emitir">
                        Emitir
                    </a>
                </code>. <br />
                Aclarar que, al no finalizar con la emision la poliza se guarda como <code>pre-aprobada</code> y no emitida.
            </p>
        @endif
        <!--Titular-->
        @include($retailerProduct->companyProduct->product->code.'.partials.block-titular' )
    </div>
    <div class="col-md-10 col-md-offset-1">
        <!--Beneficiary-->
        @include($retailerProduct->companyProduct->product->code.'.partials.block-beneficiaries' )
    </div>

    <span>
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <!--formulario cotizacion (Q) -->
            @include($retailerProduct->companyProduct->product->code.'.partials.block-generals' )
        </div>
        
        @if($header->type === 'I' && $header->issued==false)
            <div class="col-md-10 col-md-offset-1">
                <div class="text-right col-xs-12 col-md-12">
                    <a name="emitir"></a>
                    <legend class="text-bold"></legend>
                    <a href="{{ route('home', [])}}" class="btn btn-info" >
                        Guardar y Cerrar <i class="icon-floppy-disk position-right"></i>
                    </a>
                    <a href="{{ route($retailerProduct->companyProduct->product->code.'.issue', ['rp_id' => $rp_id, 'header_id' => $header_id])}}" class="btn btn-primary box-issued" >
                        Emitir <i class="icon-play position-right"></i>
                    </a>
                </div>
            </div>
        @endif
    </span>
@endsection
