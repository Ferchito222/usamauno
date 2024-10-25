<div class="row">
    <h4 class="form-wizard-title2 text-semibold text-center">
        Registro de TRANSACCIÓN
    </h4>
    <br>

    <p class="text-center">
        Cliente: {{ $header->detail->client->full_name }}
        <br>
        CI: {{ $header->detail->client->dni }} {{ $header->detail->client->complement }} {{ $header->detail->client->extension }}
        <br><br>
        Nº de Transacción de la boleta de ingreso seguro vida Ecoaguinaldo:
    </p>

    {!! Form::open(['route' => ['cashiers::order.update', 'code' => $code, 'rp_id' => $rp_id, 'header_id' => encode($header->id)],
        'method'        => 'put',
        'class'         => 'form-horizontal',
        'ng-controller' => 'HeaderEbController',
        'ng-submit'     => 'orderUpdate($event)'
    ]) !!}

    <div class="form-group">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-user-plus">Nº</i></span>
                {!! Form::text('voucher', old('voucher'), [
                    'class'        => 'form-control',
                    'placeholder'  => 'Voucher',
                    'autocomplete' => 'off',
                    'ng-model'     => 'formData.voucher',
                ]) !!}
            </div>
            <label for="location" style="color: #e95e5e;" ng-show="errors.voucher">@{{ errors.voucher[0] }}</label>
        </div>
    </div>

    <div class="text-center">
        <div style="color: #e95e5e;" ng-show="errors.check_voucher">@{{ errors.check_voucher }}</div>
        <br>

        <script ng-if="success.order">
            $(function () {
                messageAction('succes', 'El Voucher se registró correctamente');
            });
        </script>

        <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">
            Cancelar
        </button>

        {!! Form::button('Guardar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
</div>