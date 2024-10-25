<div class="row">
    <div class="col-md-12">
        <!-- Horizontal form -->
        <div class="panel panel-flat border-top-primary">
            <div class="panel-heading divhr">
                <h6 class="form-wizard-title2 text-semibold">
                    <span class="col-md-11">
                        Formulario de anulación Póliza Nº. {{ $header->prefix }} - {{$header->user->city->abbreviation}}{{ $header->issue_number }}
                    </span>
                </h6>
            </div>
            <br>

            {!! Form::open(['route' => ['td.cancel.store', 'rp_id' => $rp_id, 'header_id' => encode($header->id)],
                'method'        => 'post',
                'class'         => 'form-horizontal',
                'ng-controller' => 'CancellationController',
                'ng-submit'     => 'cancelStore($event)'
            ]) !!}

            <label class="control-label col-lg-6 label_required">Motivo de Anulación: </label>

            <div class="form-group animated">
                <div class="col-lg-12">
                    <div>
                        {!! Form::textarea('reason', null, [
                            'size'         => '4x4',
                            'class'        => 'form-control',
                            'placeholder'  => 'Motivo de Anulación',
                            'autocomplete' => 'off',
                            'ng-model'     => 'formData.reason'
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location" ng-show="errors.reason">
                        @{{ errors.reason[0] }}
                    </label>
                </div>
            </div>
            <br>

            <div class="text-right">
                <script ng-if="success.cancellation">
                    $(function () {
                        messageAction('succes', 'La Póliza fue anulada con éxito.');
                    });
                </script>

                <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cancelar
                </button>

                {!! Form::button('Anular Póliza <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>