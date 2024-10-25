<div class="row">
    <div class="col-md-12">
        <!-- Horizontal form -->
        <div class="panel panel-flat border-top-primary">
            <div class="panel-heading divhr">
                <h6 class="form-wizard-title2 text-semibold">
                    <span class="col-md-11">
                        Formulario de respuesta del usuario
                        <small class="display-block">Titular {{ $fa->detail->header->client->full_name }}</small>
                    </span>
                </h6>
            </div>
            <br>

            {!! Form::open(['route' => ['au.fa.store.answer', 'rp_id' => $rp_id, 'id' => encode($fa->id), 'id_observation' => $id_observation ],
                'method'        => 'put',
                'class'         => 'form-horizontal',
                'ng-controller' => 'FacultativeController',
                'ng-submit'     => 'storeAnswer($event)'
            ]) !!}

            <label class="control-label col-lg-3 label_required">Respuesta: </label>

            <div class="form-group animated">
                <div class="col-lg-12">
                    <div>
                        {!! Form::textarea('observation_response', null, [
                            'size'         => '4x4',
                            'class'        => 'form-control',
                            'placeholder'  => 'Respuesta',
                            'autocomplete' => 'off',
                            'ng-model'     => 'formData.observation_response'
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location"
                           ng-show="errors.observation_response">
                        @{{ errors.observation_response[0] }}
                    </label>
                </div>
            </div>
            <br>

            <div class="text-right">
                <script ng-if="success.facultative">
                    $(function () {
                        messageAction('succes', 'La respuesta fué procesada correctamente.');
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