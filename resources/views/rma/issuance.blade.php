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
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal form -->
            <div class="panel panel-flat border-top-primary">
                @include('partials.step', ['step' => '5', 'product' => 'rma'])
                @include('partials.messages')
                <div class="panel-body " ng-controller="HeaderRmaController" ng-init="uploadFile();"><br />
                    <div class="col-md-4 col-md-offset-2">
                        <div class="modal-header bg-primary">
                            <h6 class="modal-title">Póliza {{ $header->prefix }} - {{$header->user->city->abbreviation}}{{ $header->issue_number }}</h6>
                        </div>
                        <div class="panel panel-body border-top-primary text-center">
                            <p class="text-muted content-group-sm">Cotizacion/Emisión </p>
                            <div class="col-md-12">
                                <p>
                                    <a href="{{ route('certificate.show', [
                                        'code'      => 'rma',
                                        'rp_id'     => $rp_id,
                                        'type'      => 'slip',
                                        'header_id' => encode($header->id),
                                        'format'    => 'pdf',
                                    ]) }}" target="_blank" class="btn btn-info btn-labeled btn-xlg col-lg-12">
                                        <b><i class="icon-printer4"></i></b> Ver Slip de Cotización
                                    </a>
                                </p>
                                <div class="col-md-6">&nbsp;</div>
                                <p>
                                    <a href="{{ route('certificate.show', [
                                        'code'      => 'rma',
                                        'rp_id'     => $rp_id,
                                        'type'      => 'certificate',
                                        'header_id' => encode($header->id),
                                        'format'    => 'pdf',
                                    ]) }}" ng-click="showBoxAttach{{ strtolower($header->prefix) }}($event)" target="_blank"
                                       class="btn btn-info btn-labeled btn-xlg col-lg-12">
                                        <b><i class="icon-printer4"></i></b>
                                        Ver Certificado de Emisión
                                    </a>
                                </p>
                                <div class="col-md-6">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="box-attach-{{ strtolower($header->prefix) }}" style=" {{ $header->read ? '' : 'display: none;' }}">
                        <div class="modal-header bg-success">
                            <h6 class="modal-title text-center">CARGAR CERTIFICADO ADJUNTO</h6>
                        </div>
                        <div class="panel panel-body border-top-primary text-center">
                            <div class="col-md-12">
                                @if(! is_null($header->file))
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

            </div>
            <!-- /horizotal form -->
        </div>
    </div>

<!-- modal -->
@include('partials.modal_content_report')
<!-- /modal -->
@endsection
