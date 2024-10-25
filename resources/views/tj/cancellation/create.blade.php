<div class="row">
    <div class="col-md-12">
        <!-- Horizontal form -->
        <div class="panel panel-flat">
            <div class="col-md-12">
                <h3>
                    Formulario de anulación Póliza Nº. {{ $header->prefix }} - {{ $header->issue_number }}
                </h3>
            </div>

            {!! Form::open(['route' => [$retailerProduct->companyProduct->product->code.'.cancel.store', 'rp_id' => $rp_id, 'header_id' => encode($header->id)],
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
                            'id' => 'reason',
                            'ng-model'     => 'formData.reason'
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location" ng-show="errors.reason">
                        @{{ errors.reason[0] }}
                    </label>
                </div>
            </div>
            <br>

            <div class="form-group text-right">
                <div class="col-lg-12">
                <script ng-if="success.cancellation">
                    $(function () {
                        messageAction('succes', 'El Certificado fue anulado correctamente.');
                    });
                </script>

                <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cancelar
                </button>

                {!! Form::button('Anular Póliza', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>