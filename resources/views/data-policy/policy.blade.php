<div class="row">
    @if($code === 'au' || $code === 'td' || $code === 'ema' || $code === 'rma')
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Inicio de Vigencia</label>
                <input type="date" class="form-control disabled-policy" placeholder="Inicio de Vigencia"
                       name="validity_start" value="{{ date('Y-m-d', strtotime($header->validity_start)) }}">
            </div>
        </div>
    @endif

    @if($code === 'de')
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Tipo de Cobertura</label>
                {!! SelectField::input('coverage', $data['coverages']->toArray(), [
                    'class' => 'form-control disabled-policy',
                    ], old('coverage', $header->coverage->id))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Monto Actual Solicitado</label>
                <input type="text" class="form-control disabled-policy" placeholder="Monto Actual Solicitado"
                       name="amount_requested" value="{{ $header->amount_requested }}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Moneda</label>
                {!! SelectField::input('currency', $data['currencies']->toArray(), [
                    'class' => 'form-control disabled-policy',
                    ], old('currency', $header->currency))
                !!}
            </div>
        </div>
    @endif

    @if($code === 'de' || $code === 'au' || $code === 'td' || $code === 'ema' || $code === 'rma')
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Plazo del Credito</label>
                <div class="input-group">
                    <div class="col-lg-4">
                        <input type="text" class="form-control disabled-policy" placeholder="Plazo del Credito"
                               name="term" value="{{ $header->term }}" autocomplete="off">
                    </div>
                    <div class="col-lg-8">
                        {!! SelectField::input('type_term', $data['term_types']->toArray(), [
                            'class' => 'form-control disabled-policy',
                            ], old('type_term', $header->type_term))
                        !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($code === 'au' || $code === 'td' || $code === 'ema' || $code === 'rma')
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Forma de Pago</label>
                {!! SelectField::input('payment_method', $data['payment_methods']->toArray(), [
                    'class' => 'form-control disabled-policy',
                    ], old('payment_method', $header->payment_method))
                !!}
            </div>
        </div>
        @if($code != 'td')
            <div class="col-md-3">
                <div class="form-group text-center">
                    <label class="label label-default">Capital</label>
                    {!! SelectField::input('capital', $data['capital']->toArray(), [
                        'class' => 'form-control disabled-policy',
                        ], old('capital', $header->capital))
                    !!}
                </div>
            </div>
        @endif
    @endif
</div>
<div class="row">
    @if($code === 'de')
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Producto</label>
                {!! SelectField::input('credit_product', $data['credit_products']->toArray(), [
                    'class' => 'form-control disabled-policy',
                    ], old('credit_product', $header->ad_credit_product_id))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Tipo de Movimiento</label>
                {!! SelectField::input('movement_type', $data['movement_types']->toArray(), [
                    'class' => 'form-control disabled-policy',
                    ], old('movement_type', $header->movement_type))
                !!}
            </div>
        </div>
    @endif

    @if($code === 'de' || $code === 'au' || $code === 'td' || $code === 'ema' || $code === 'rma')
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Número de Operación</label>
                <input type="text" class="form-control disabled-policy" placeholder="Número de Operación"
                       name="operation_number" value="{{ $header->operation_number }}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Número de Póliza</label>
                {!! SelectField::input('policy_number', $data['policies']->toArray(), [
                    'class' => 'form-control disabled-policy',
                    ], old('policy_number', $header->policy_number))
                !!}
            </div>
        </div>
    @endif
</div>
<hr>