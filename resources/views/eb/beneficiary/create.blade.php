<div class="row">
    <div class="col-md-12">
        <div class="panel panel-flat border-top-primary">
            <div class="panel-heading divhr">
                <h6 class="form-wizard-title2 text-semibold">
                    <span class="col-md-11">
                        <span class="form-wizard-count">3</span>
                        Datos del Beneficiario
                        <small class="display-block">Cliente {{ $header->detail->client->full_name }}</small>
                    </span>
                </h6>
            </div>
            <br>

            @if(session('error_beneficiary'))
                <div class="alert bg-danger alert-styled-right">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                class="sr-only">Close</span></button>
                    <span class="text-semibold">{{ session('error_beneficiary') }}</span>.
                </div>
            @endif

            {!! Form::open(['route' => ['eb.beneficiary.store', 'rp_id' => $rp_id, 'header_id' => $header_id],
                'method'        => 'post', 
                'class'         => 'form-horizontal', 
                'ng-controller' => 'BeneficiaryController',
                'ng-submit'     => 'store($event)'
            ]) !!}

            @include('eb.beneficiary.partials.inputs')

            <div class="text-right">
                <script ng-if="success.beneficiary">
                    $(function () {
                        messageAction('succes', 'El Beneficiario fue registrado con éxito');
                    });
                </script>

                <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cancelar
                </button>

                {!! Form::button('Guardar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>