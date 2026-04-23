@extends('layout')

@section('header')
    @include('partials.header-home')
@endsection

@section('menu-main')
    @include('partials.menu-main')
@endsection

@section('menu-header')

@endsection

@section('content')

    <div class="row panel">
        <div class="col-md-12">
            <div class="border-top-primary">
                @include('partials.messages')
                <div class="panel-body">
                    <div class="col-xs-12" ng-controller="CancellationController">
                        <h6>Anulación de Certificados</h6>
                        <hr />
                        {!! Form::open(['url' => request()->fullUrl(), 'method' => 'get', 'class' => 'form-horizontal']) !!}
                        <div class="col-xs-12 col-md-12">
                            @include('report.partials.inputs-search2')
                        </div>
                        {!! Form::close() !!}
                        <div class="clearfix">
                            <small class="help-block">Lista de Certificados Vida Grupo:</small>
                        </div>
                        <div style="width: 100%; overflow: scroll;" ng-controller="ReportController">
                            <table width="100%" class="table-bordered table-hover gridview small_body" border="1">
                                <thead>
                                    <tr>
                                        <th>NRO. DE PÓLIZA</th>
                                        <th>CLIENTE</th>
                                        <th>C.I.</th>
                                        <th>CLIENTE TOMADOR</th>
                                        <th>PRIMA TOTAL</th>
                                        <th>FORMA DE PAGO</th>
                                        <th>PERIODICIDAD</th>
                                        <th>USUARIO</th>
                                        <th>SUCURSAL / AGENCIA</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($headers as $header)
                                        @foreach ($header->details as $index => $detail)
                                                                    <tr>
                                                                        <td style="font-weight: bold;">{{ $header->policy_number }}</td>
                                                                        <td>{{ $detail->client->full_name }}</td>
                                                                        <td>{{ $detail->client->dni }} {{ $detail->client->extension }}</td>
                                                                        <td>{{ $detail->holder == 1 ? 'SI' : 'NO' }}</td>
                                                                        <td>{{ $header->total_premium }} {{ $header->currency }}</td>
                                                                        <td>{{ config('base.payment_methods_vi.' . $header->payment_method) }}</td>
                                                                        <td>{{ config('base.periods.' . $header->period) }}</td>
                                                                        <td>{{ $header->user->full_name }}</td>
                                                                        <td>
                                                                            {{ !is_null($header->user->city) ? $header->user->city->name : '' }}
                                                                            {{ !is_null($header->user->agency) ? '/ ' . $header->user->agency->name : '' }}
                                                                        </td>
                                                                        <td>
                                                                            <ul class="icons-list">
                                                                                <li class="dropdown">
                                                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                                        <i class="icon-menu9"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                                        <li>
                                                                                            <a href="{{ route($retailerProduct->companyProduct->product->code . '.cancel.create', ['rp_id' => $rp_id, 'header_id' => encode($header->id)]) }}"
                                                                                                ng-click="cancelCreate($event)">
                                                                                                Anular Certificado
                                                                                            </a>
                                                                                            {{-- <a href="#" id="abrirModalBtn">
                                                                                                Anular Certificado
                                                                                            </a> --}}
                                                                                        </li>
                                                                                        <li>
                                                                                            <a href="{{ route('certificate.show', [
                                                'code' => $retailerProduct->companyProduct->product->code,
                                                'rp_id' => $rp_id,
                                                'type' => 'certificate',
                                                'header_id' => encode($header->id),
                                                'format' => 'pdf',
                                            ])}}" target="_blank">
                                                                                                Ver Certificado de Emisión
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br />
                        <div style="text-align: center;" class="small_body">
                            {!! $headers->appends(request()->all())->render() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Overlay (fondo oscuro) */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        /* Contenedor del modal */
        .modal-container {
            background-color: white;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease;
            margin: 5% 35%;
        }

        /* Cabecera del modal */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid #ddd;
        }

        /* Cuerpo del modal (aquí va tu DIV original) */
        .modal-body {
            padding: 20px;
        }

        /* Pie del modal */
        .modal-footer {
            padding: 12px;
            text-align: right;
            border-top: 1px solid #ddd;
        }

        /* Botón cerrar */
        .close-btn {
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
        }

        /* Animación de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Clases para mostrar/ocultar */
        .modal-overlay.show {
            display: flex;
        }
    </style>
    <!-- Estructura del modal -->
    <div id="miModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-body">
                <!-- Aquí se inyectará el contenido del DIV original -->
                {{-- @include('vd.cancellation.create') --}}
            </div>
        </div>
    </div>
    <script>
        // Capturar elementos
        const abrirBtn = document.getElementById('abrirModalBtn');
        const modal = document.getElementById('miModal');
        const modalBody = document.querySelector('#miModal .modal-body');
        const cerrarBtns = document.querySelectorAll('#cerrarModalBtn, #cerrarModalFooterBtn');
        const contenidoOriginal = document.getElementById('miContenidoOriginal');

        // Función para abrir modal
        function abrirModal() {
            // Copiar contenido del DIV original al body del modal
            // modalBody.innerHTML = contenidoOriginal.innerHTML;
            modal.classList.add('show');
            document.body.style.overflow = 'hidden'; // evitar scroll detrás
        }

        // Función para cerrar modal
        function cerrarModal() {
            modal.classList.remove('show');
            document.body.style.overflow = ''; // restaurar scroll
        }

        // Eventos
        abrirBtn.addEventListener('click', abrirModal);
        cerrarBtns.forEach(btn => {
            btn.addEventListener('click', cerrarModal);
        });

        // Cerrar si se hace clic fuera del contenido del modal (en el overlay)
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                cerrarModal();
            }
        });
    </script>
@endsection