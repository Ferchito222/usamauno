<div class="row">
    <div class="col-md-12">
        <!-- Horizontal form -->
        <div class="panel panel-flat border-top-primary">
            <div class="panel-heading divhr">
                <h6 class="form-wizard-title2 text-semibold">
                    <span class="col-md-11">
                        Automotores
                    </span>
                </h6>
            </div>

            {!! Form::open(['route' => ['au.coverage.store',
                'rp_id' => $rp_id,
                'de_id' => $de_id,
                'rp_de' => request()->get('rp_de'),
            ],
                'method'        => 'post',
                'class'         => 'form-horizontal',
                'ng-controller' => 'HeaderDeController',
                'ng-submit'     => 'storeCoverage($event)',
            ]) !!}

            <div class="panel-body ">
                <label class="col-lg-12 control-label label_required">Cliente: </label>
                <div class="alert-info" style="padding-bottom: 10px;">
                    @foreach($de->details as $key => $detail)
                        <label class="radio-inline" style=" margin-left: 50px;">
                            {!! Form::radio('client', encode($detail->client->id), false, [
                                'ng-model' => 'formData.client',
                            ]) !!}

                            {{ $detail->client->full_name }}
                        </label><br>
                    @endforeach
                    <label id="location-error" class="validation-error-label" for="location"
                           ng-show="errors['client']">
                        @{{ errors['client'][0] }}
                    </label>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label class="col-lg-9 control-label label_required">Forma de Pago: </label>
                        <div class="col-lg-12 form-group">
                            <select ng-options="t.name for t in data.payment_methods track by t.id"
                                    ng-model="formData.payment_method" class="form-control">
                            </select>
                            <label id="location-error" class="validation-error-label" for="location"
                                   ng-show="errors['payment_method.id']">
                                @{{ errors['payment_method.id'][0] }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-9 control-label label_required">Moneda: </label>
                        <div class="col-lg-12 form-group">
                            <select ng-options="t.name for t in data.currencies track by t.id"
                                    ng-model="formData.currency" class="form-control">
                            </select>
                            <label id="location-error" class="validation-error-label" for="location"
                                   ng-show="errors['currency.id']">
                                @{{ errors['currency.id'][0] }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div class="form-group">
                        <label class="col-lg-12 control-label label_required">Plazo del Crédito: </label>
                        <div class="col-lg-3">
                            {!! Form::text('term', old('term'), [
                                'class'        => 'form-control',
                                'autocomplete' => 'off',
                                'ng-model'     => 'formData.term',
                            ]) !!}
                            <label id="location-error" class="validation-error-label" for="location"
                                   ng-show="errors['term']">
                                @{{ errors['term'][0] }}
                            </label>
                        </div>
                        <div class="col-lg-9">
                            <select ng-options="t.name for t in data.term_types track by t.id"
                                    ng-model="formData.type_term" class="form-control">
                            </select>
                            <label id="location-error" class="validation-error-label" for="location"
                                   ng-show="errors['type_term.id']">
                                @{{ errors['type_term.id'][0] }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="text-right">
                    <script ng-if="success.coverage">
                        $(function () {
                            messageAction('succes', 'La cobertura fue inicializada con éxito.');
                        });
                    </script>

                    <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Continuar <i class="icon-arrow-right14 position-right"></i>
                    </button>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $("select.select-search").select2({
                    dropdownParent: "#popup"
                });
            });
        </script>
        <!-- /horizotal form -->
    </div>
</div>