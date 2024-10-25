@extends('body')
@var $step=4
@section('body')
<div class="panel-body" ng-controller="HeaderDeController" ng-init="uploadFile();">
    <div class="col-md-4 col-md-offset-2">
        <div class="modal-header bg-primary">
            <h6 class="modal-title" style="text-transform: uppercase;">Póliza {{ $header->prefix }}-{{ $header->issue_number }}</h6>
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
                                            ])}}" ng-click="showBoxAttach($event)" target="_blank"
                       class="btn btn-info btn-labeled btn-xlg col-lg-12">
                        <b><i class="icon-printer4"></i></b>
                        Ver Certificado de Emisión
                    </a>
                </p>
                <div class="col-md-6">&nbsp;</div>
            </div>
        </div>
    </div>
    <div class="col-md-4" id="box-attach" style=" {{ $header->read ? '' : 'display: none;' }}">
        <div class="modal-header bg-success">
            <h6 class="modal-title text-center">CARGAR CERTIFICADO ADJUNTO</h6>
        </div>
        <div class="panel panel-body border-top-primary text-center">
            <div class="col-md-12">
                @if($header->file!='')
                    <a href="{{ route(strtolower($header->prefix).'.attach.file', ['file' => $header->file]) }}"
                       target="_blank"
                       class="btn btn-success btn-labeled btn-xlg col-lg-12">
                        <b><i class="icon-printer4"></i></b> Ver Certificado Adjunto
                    </a>
                @else
                    <input type="hidden" id="url-upload"
                           value="{{ route(strtolower($header->prefix).'.upload.certificate', ['rp_id' => $rp_id, 'header_id' => ':header_id']) }}">

                    <input type="file" nv-file-select uploader="uploader"
                           options="{formData: [{ 'id' : '{{ $header_id }}' }]}" id="file"
                           class="input-file"/>
                    <label for="file" data-progress="box-progress">
                        <span>Escoge un archivo...</span>
                    </label>
                    <small class="help-block">Adjuntar Certificado Firmado.</small>
                    <div class="box-progress" style="display: none;">
                        <label class="validation-valid-label"
                               ng-show="success.message">@{{ success.message }}</label>
                        <label class="validation-error-label"
                               ng-show="errors.message">@{{ errors.message }}</label>
                        <div class="progress" style="">
                            <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.queue[0].progress + '%' }"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
