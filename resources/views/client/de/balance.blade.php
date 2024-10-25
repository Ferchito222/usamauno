<div class="row">
    <div class="col-md-12">
        <!-- Horizontal form -->
        <div class="panel panel-flat border-top-primary">
            <div class="panel-heading divhr">
                <h6 class="form-wizard-title2 text-semibold">
                    <span class="col-md-11">
                        <span class="form-wizard-count">4</span>
                        Datos del Saldo deudor
                        <small class="display-block">Cliente {{ $detail->client->full_name }}</small>
                    </span>
                </h6>
            </div>

            @if(session('error_detail'))
                <div class="alert bg-danger alert-styled-right">
                    <button type="button" class="close" data-dismiss="alert"><span>×</span><span
                                class="sr-only">Close</span></button>
                    <span class="text-semibold">{{ session('error_detail') }}</span>.
                </div>
            @endif

            <div class="alert alert-info alert-styled-left alert-arrow-left alert-component">
                <!--<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>-->
                <h6 class="alert-heading text-semibold">Importante</h6>
                Comprobar y validar saldos para deuda directa titular en créditos paralelos y refinanciados.
            </div>

            {!! Form::open(['route' => ['de.detail.balance.update', 'rp_id' => $rp_id, 'header_id' => encode($header->id), 'detail_id' => encode($detail->id)],
                'method'        => 'put',
                'class'         => 'form-horizontal',
                'ng-controller' => 'DetailDeController as detailDe',
                'ng-submit'     => 'detailDe.updateBalance($event)'
            ]) !!}

            <div class="form-group">
                <label class="control-label col-lg-3"></label>
                <div class="col-lg-9">
                    Tipo de Movimiento:
                    <span class="no-margin text-semibold">{{ config('base.movement_types.' . $header->movement_type) }}</span>
                </div>
                <br>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-5 label_required">Monto Actual Solicitado: </label>
                <div class="col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <strong>{{ $header->currency === 'BS' ? 'Bs.' : $header->currency }}</strong>
                        </span>
                        {!! Form::text('amount_requested', null, [
                            'class'        => 'form-control',
                            'readonly'     => 'true',
                            'placeholder'  => 'Monto Actual Solicitado',
                            'autocomplete' => 'off',
                            'type'         => 'number',
                            'ng-model'     => 'formData.amount_requested',
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location"
                           ng-show="errors.amount_requested">@{{ errors.amount_requested[0] }}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="alert alert-info text-center" style="padding: 10px;"
                     ng-if="formData.movement_type == 'FS' || formData.movement_type == 'AD'">
                    No tomar en cuenta la Solicitud Actual.
                </div>
                <div class="alert alert-danger text-center" style="padding: 10px;"
                     ng-if="formData.movement_type == 'LC'">
                    Llenar solo en caso que el cliente tenga créditos adicionales, FUERA de la Línea.
                </div>

                <label class="control-label col-lg-5 label_required">Saldo deudor actual del asegurado: </label>
                <div class="col-lg-5">
                    <div class="input-group">
                        <span class="input-group-addon"><strong>Bs.</strong></span>
                        {!! Form::text('balance', null, [
                            'class'        => 'form-control',
                            'placeholder'  => 'Saldo deudor actual del asegurado (Bs.)',
                            'autocomplete' => 'off',
                            'type'         => 'number',
                            'ng-model'     => 'formData.balance',
                            'ng-keyup'     => 'cumulus()',
                            $header->movement_type == 'FS' ? '' : '',
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location"
                           ng-show="errors.balance">@{{ errors.balance[0] }}</label>
                </div>
                <!--<div class="col-lg-2">
                    <a class="btn btn-info"
                       href="{{ route('client.search', ['rp_id' => $rp_id, 'header_id' => encode($header->id), 's_dni' => $detail->client->dni, 's_complement' => $detail->client->complement, 'refresh' => encode($detail->client->id) ]) }}"
                       ng-click="detailDe.getBalance($event);"
                       role="button" title="Obtener el Saldo Deudor">
                        <span ng-click="detailDe.getBalance($event)" class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                    </a>
                </div>-->
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <input type="checkbox" name="read_field" ng-model="formData.read_field" /> Editar Montos Créditos Paralelos/Operación a cancelar</label>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-5 label_required">Monto Créditos Paralelos: </label>
                <div class="col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon"><strong>Bs.</strong></span>
                        {!! Form::text('parallel_credit', null, [
                            'class'        => 'form-control',
                            'id'           => 'parallel_credit',
                            'placeholder'  => 'Monto Créditos Paralelos (Bs.)',
                            'autocomplete' => 'off',
                            'type'         => 'number',
                            'ng-model'     => 'formData.parallel_credit',
                            'ng-keyup'     => 'refinancing()',
                        ]) !!}
                    </div>
                    <mall class="help-block alert-danger" ng-if="small_help">Verificar Monto Créditos Paralelos</mall>
                    <label class="validation-error-label" ng-show="errors.parallel_credit">@{{ errors.parallel_credit[0] }}</label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-5 label_required">Monto Actual Acumulado: </label>
                <div class="col-lg-7">
                    <h6 class="text-muted">
                        @{{ formData.cumulus | currency : 'Bs. ' }}
                        @if($header->currency === 'USD')
                            <br>
                            <span style="font-size: 1.2rem; color: #455A64;">
                                Monto expresado en bolivianos por el tipo de cambio {{ $retailer->exchangeRate->bs_value }}
                            </span>
                        @endif
                    </h6>
                    <div class="input-group" ng-if="formData.movement_type == 'LC'">
                        <span class="input-group-addon"><i class="icon-cash2"></i></span>
                        {!! Form::text('cumulus', null, [
                            'class'        => 'form-control',
                            'placeholder'  => 'Monto Total Acumulado (Bs.)',
                            'autocomplete' => 'off',
                            'type'         => 'number',
                            'ng-model'     => 'formData.cumulus',
                        ]) !!}
                    </div>
                    <div class="alert-info text-center" style="padding: 5px;" ng-if="formData.movement_type == 'LC'">
                        Sumatoria del total de operaciones vigentes, incluyendo el Monto Actual Solicitado.
                    </div>
                    <div class="alert-warning text-center" style="padding: 5px;" ng-if="data.cumulus">
                        El monto total acumulado debe ser mayor o igual al monto actual solicitado y al saldo deudor
                        actual.
                    </div>
                    <label class="validation-error-label" ng-show="errors.cumulus">@{{ errors.cumulus[0] }}</label>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-5 label_required">Monto de Operación a cancelar: </label>
                <div class="col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon"><strong>Bs.</strong></span>
                        {!! Form::text('refinancing', null, [
                            'class'        => 'form-control',
                            'id'           => 'refinancing',
                            'placeholder'  => 'Monto Refinanciado (Bs.)',
                            'autocomplete' => 'off',
                            'type'         => 'number',
                            'ng-model'     => 'formData.refinancing',
                            'ng-keyup'     => 'refinancing()',
                        ]) !!}
                    </div>
                    <mall class="help-block alert-danger" ng-if="small_help">Verificar Monto de Operación a cancelar</mall>
                    <label class="validation-error-label" ng-show="errors.refinancing">@{{ errors.refinancing[0] }}</label>
                </div>
            </div>
            <br>
            <div class="text-right">
                <mall class="help-block alert-danger" ng-if="message_buton">El Monto de Operación a cancelar no puede superar el <strong>Saldo deudor actual del asegurado</strong> </mall>
                <script ng-if="success.beneficiary">
                    $(function () {
                        messageAction('succes', 'El Saldo Deudor fue actualizado correctamente');
                    });
                </script>

                <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cancelar
                </button>
                
                {!! Form::button('Actualizar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary', 'id'=>'btn_balance']) !!}
                
            </div>
            {!! Form::close() !!}
        </div>

        <!-- /horizotal form -->
    </div>
</div>
