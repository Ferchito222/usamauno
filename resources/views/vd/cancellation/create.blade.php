<div class="row">
    <div class="col-md-12">
        <!-- Horizontal form -->
        <div class="panel panel-flat border-top-primary">
            <div class="panel-heading divhr">
                <h6 class="form-wizard-title2 text-semibold">
                    <span class="col-md-11">
                        Formulario de anulación Póliza Nº.
                        {{ explode('-', $header->policy_number)[0] }}-{{ $header->issue_number }}{{$header->version_number}}
                    </span>
                </h6>
            </div>
            <br>

            {!! Form::open([
    'route' => ['vd.cancel.store', 'rp_id' => $rp_id, 'header_id' => encode($header->id)],
    'method' => 'post',
    'class' => 'form-horizontal',
    'ng-controller' => 'CancellationController',
    'ng-submit' => 'cancelStore($event)'
]) !!}
            <label class="control-label col-lg-12 label_required">Tipo de anulación: </label>
            <div class="form-group animated">
                <div class="col-lg-12">
                    <div>
                        {!! SelectField::input('ad_annulleds_id', $data['annulleds']->toArray(), [
    'class' => 'form-control',
    'id' => 'ad_annulleds_id',
    'ng-change' => 'onChange()',
    'ng-model' => 'formData.ad_annulleds_id'
], old('ad_annulleds_id')) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location"
                        ng-show="errors.ad_annulleds_id">
                        @{{ errors.ad_annulleds_id[0] }}
                    </label>
                </div>
            </div>
            <br>

            <label class="control-label col-lg-6 label_required">Motivo de Anulación: </label>

            <div class="form-group animated">
                <div class="col-lg-12">
                    <div>
                        {!! Form::textarea('reason', null, [
    'size' => '4x4',
    'class' => 'form-control',
    'placeholder' => 'Motivo de Anulación',
    'autocomplete' => 'off',
    'ng-model' => 'formData.reason'
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
                {{-- <button type="button" class="btn border-slate text-slate-800 btn-flat"
                    id="cerrarModalFooterBtn">Cancelar
                </button> --}}

                {!! Form::button('Anular Póliza <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>