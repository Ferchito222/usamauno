@extends('body')
@var $step=4
@section('body')
<div class="col-md-4 col-md-offset-2">
    <div class="modal-header bg-primary">
        <h6 class="modal-title">Póliza {{ $header->policy_number }}</h6>
    </div>
    <div class="panel panel-body border-top-primary text-center">
        <p class="text-muted content-group-sm">Cotizacion/Emisión </p>
        <div class="col-md-12">
            <p>
                <a href="{{ route('certificate.show', [
                                                'code' => $retailerProduct->companyProduct->product->code,
                                                'rp_id' => $rp_id,
                                                'type' => 'certificate',
                                                'header_id' => encode($header->id),
                                                'format' => 'pdf',
                                        ])}}"  target="_blank"
                   class="btn btn-info btn-labeled btn-xlg col-lg-12">
                    <b><i class="icon-printer4"></i></b>
                    Ver Certificado de Emisión
                </a>
            </p>
            <div class="col-md-6">&nbsp;</div>
        </div>
    </div>
</div>
@endsection