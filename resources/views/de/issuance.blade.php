@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span
                            class="text-semibold">Seguro de Desgravamen</span></h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">Desgravamen</a></li>
                    <li class="active">Cotizar</li>
                </ul>
            </div>

        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal form -->
            <div class="panel panel-flat border-top-primary">
                <div class="panel-heading divhr">
                    <div class="steps-basic2 wizard">
                        <div class="steps">
                            @include('partials.steps', [ 'product' => 'de', 'step' => 5, 'length' => 5 ])
                        </div>
                    </div>
                    <button style="float: right;" type="button" class="btn btn-rounded btn-default text-right"
                            title="Detalle de producto" data-placement="right" data-toggle="modal"
                            data-target="#modal_theme_primary">
                        <i class="icon-question7"></i> Producto
                    </button>
                </div>
                <div class="clearfix"></div>
                @if(session('error_header'))
                    <script>
                        $(function () {
                            messageAction('error', "{{ session('error_header') }}");
                        });
                    </script>
                @endif

                @if(session('success_header'))
                    <script>
                        $(function () {
                            messageAction('succes', "{{ session('success_header') }}");
                        });
                    </script>
                @endif

                <div class="panel-body" ng-controller="HeaderDeController" ng-init="uploadFile();">
                    <div class="col-md-4 col-md-offset-2">
                        <div class="modal-header bg-primary rounded-top10">
                            <h6 class="modal-title">Certificado {{ $header->prefix }} - {{ $header->issue_number }}{{$header->version_number}}</h6>
                        </div>
                        <div class="panel panel-body border-top-primary text-center rounded-bottom10">
                            <p class="text-muted content-group-sm">Certificados</p>
                            <div class="col-md-12">
                                
                                <p>
                                    <a href="{{ route('certificate.show', [
                                        'code'      => 'de',
                                        'rp_id'     => $rp_id,
                                        'type'      => 'certificate',
                                        'header_id' => $header_id,
                                        'format'    => 'pdf',
                                    ]) }}" ng-click="showBoxAttach($event)" target="_blank"
                                       class="btn btn-info btn-labeled btn-xlg col-lg-12">
                                        <b><i class="icon-printer4"></i></b>
                                        Ver Certificado de Emisión -1
                                    </a>
                                </p>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4" id="box-attach" style=" {{ $header->read ? '' : 'display: none;' }}">
                        <div class="modal-header bg-success rounded-top10">
                            <h6 class="modal-title text-center">CARGAR CERTIFICADO ADJUNTO</h6>
                        </div>
                        <div class="panel panel-body border-top-primary text-center rounded-bottom10">
                            <div class="col-md-12">
                                @if(! is_null($header->file))
                                    <a href="{{ route('de.attach.file', ['file' => $header->file]) }}"
                                       target="_blank"
                                       class="btn btn-success btn-labeled btn-xlg col-lg-12">
                                        <b><i class="icon-printer4"></i></b> Ver Certificado Adjunto
                                    </a>
                                @else
                                    <input type="hidden" id="url-upload"
                                           value="{{ route('de.upload.certificate', ['rp_id' => $rp_id, 'header_id' => ':header_id']) }}">

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

                    {{--@if(! is_null($retailerProduct->subProducts) && $header->creditProduct->slug === 'PMO')
                        <div class="col-md-4">
                            <div class="modal-header bg-success">
                                <h6 class="modal-title">Sub-Productos</h6>
                            </div>
                            <div class="panel panel-body border-top-primary text-center">
                                <p class="text-muted content-group-sm">Selecciona nuestros productos </p>
                                <div class="col-md-12">
                                    @foreach($retailerProduct->subProducts as $subProduct)
                                        @if ($subProduct->companyProduct->product->code === 'vi')
                                            <p>
                                                <a href="{{ route('de.vi.sp.list', [
                                                    'rp_id'     => $rp_id,
                                                    'header_id' => $header_id,
                                                    'sp_id'     => encode($subProduct->productCompany->id)
                                                ]) }}" class="btn btn-default col-lg-12 btn-xlg">
                                                    <i class="icon-hyperlink"></i> {{ $subProduct->companyProduct->product->name }}
                                                </a>
                                            </p>
                                            <div class="col-md-6">&nbsp;</div>

                                            @var $clientVi = false

                                            @foreach ($header->details as $detail)
                                                @if ($detail->client->detailsVi->count() > 0)
                                                    @var $clientVi = true
                                                @endif
                                            @endforeach

                                            @if ($clientVi)
                                                <p>
                                                    <a href="{{route('create_modal_slip', [
                                                        'id_retailer_product' => encode($subProduct->productCompany->id),
                                                        'id_header'           => encode($header->id),
                                                        'text'                => 'issuance',
                                                        'type'                => 'IMPR'
                                                    ]) }}" id="vi"
                                                       class="btn btn-success btn-labeled btn-xlg col-lg-12 open_modal">
                                                        <b><i class="icon-printer4"></i></b> Ver Certificado Vida Grupo
                                                    </a>
                                                </p>
                                                <div class="col-md-6">&nbsp;</div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif--}}

                    {{--<div class="col-md-4" ng-controller="HeaderDeController">
                        <div class="modal-header bg-info warranty-box" data-toggle="tooltip" data-placement="top"
                             title="Seleccione una garantía en caso que el cliente haya solicitado expresamente tomar
                             un seguro con la aseguradora ofertada." style="cursor: pointer;">
                            <h6 class="modal-title">
                                <span id="warranty-icon" class="glyphicon glyphicon-plus"
                                      style="margin-right: 10px;"></span>
                                Garantías
                            </h6>
                        </div>
                        <div class="panel panel-body border-top-primary text-center" id="warranty-box"
                             style="display: none;">
                            <div class="col-md-12">
                                @foreach($retailer->retailerProducts as $retailerProduct)
                                    @if($retailerProduct->type === 'MP' && $retailerProduct->warranty)
                                        @var $coverage  = false;
                                        @var $header_cw = null;

                                        @if(! is_null($header->coverageWarranty))
                                            @if($retailerProduct->companyProduct->product->code === 'au' && ! is_null($header->coverageWarranty->au))
                                                @var $coverage  = true;
                                                @var $header_cw = $header->coverageWarranty->au->id;
                                            @elseif($retailerProduct->companyProduct->product->code === 'td' && ! is_null($header->coverageWarranty->td))
                                                @var $coverage  = true;
                                                @var $header_cw = $header->coverageWarranty->td->id;
                                            @endif
                                        @endif

                                        @if($coverage)
                                            <a href="{{ route('create_modal_slip', [
                                                'id_retailer_product' => encode($retailerProduct->id),
                                                'id_header'           => encode($header_cw),
                                                'text'                => 'issuance',
                                                'type'                => 'IMPR'
                                            ]) }}"
                                               class="btn btn-info btn-labeled btn-xlg col-lg-12 open_modal"
                                               data-toggle="modal" data-target="#modal_general">
                                                <b><i class="icon-printer4"></i></b> Ver Certificado
                                                de {{ $retailerProduct->companyProduct->product->name }}
                                            </a>
                                        @else
                                            <p>
                                                <a href="{{ route($retailerProduct->companyProduct->product->code . '.coverage.create', [
                                                    'rp_id' => encode($retailerProduct->id),
                                                    'de_id' => $header_id,
                                                    'rp_de' => $rp_id
                                                ]) }}" class="btn btn-default col-lg-12 btn-xlg"
                                                   ng-click="createCoverage($event)">
                                                    <i class="icon-hyperlink"></i> {{ $retailerProduct->companyProduct->product->name }}
                                                </a>
                                            </p>
                                        @endif

                                        <div class="col-md-6">&nbsp;</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>--}}
                </div>

            </div>
            <!-- /horizotal form -->
        </div>
    </div>

    <!-- modal -->
    @include('partials.modal_content_report')
            <!-- /modal -->

@endsection
