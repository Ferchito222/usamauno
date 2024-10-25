<input type="hidden" id="_mc" name="_mc"
       value="{{ route('de.fa.mc.create', ['rp_id' => $rp_id, 'id' => encode($fa->id)]) }}">
       
<input type="hidden" id="rate_accidental_death" name="rate_accidental_death"
       value="{{ ($fa->detail->header->retailerProduct->rates()->doesntHave('creditProduct')->count())?$fa->detail->header->retailerProduct->rates()->doesntHave('creditProduct')->first()->rate_accidental_death:0 }}">
<input type="hidden" id="accidental_death_obs" name="accidental_death_obs"value="{{ config('base.observations_message.accidental_death') }}">
<input type="hidden" id="credit_product_slug" name="credit_product_slug"value="{{ $fa->detail->header->creditProduct->slug }}">

<div class="form-group">
    <label class="control-label col-lg-3 label_required">Aprobado: </label>
    <div class="col-lg-9">
        <div class="input-group">
            <label class="radio-inline">
                {!! Form::radio('approved', 1, false, [
                    'ng-click' => 'approved = true; state = false; observation = true; mcEnabled = false;',
                    'ng-model' => 'formData.approved'
                ]) !!}
                SI
            </label>
            <label class="radio-inline">
                {!! Form::radio('approved', 0, false, [
                    'ng-click'   => 'approved = false; state = false; observation = true; formData.surcharge = 0; mcEnabled = false;',
                    'ng-model'   => 'formData.approved',
                    'ng-checked' => 'true',
                ]) !!}
                NO
            </label>
            <label class="radio-inline">
                {!! Form::radio('approved', 2, false, [
                    'ng-click' => 'approved = false; state = true;',
                    'ng-model' => 'formData.approved'
                ]) !!}
                Pendiente
            </label>
            @if(!in_array($fa->detail->header->creditProduct->slug, ['PMO', 'PLT']))
                <label class="radio-inline">
                    {!! Form::radio('approved', 3, false, [
                        'ng-click' => 'approved = false; state = false; extras=true;current_rate_accidental=true;',
                        'ng-model' => 'formData.approved'
                    ]) !!}
                    Muerte accidental
                </label>
            @endif
        </div>
        <label id="location-error" class="validation-error-label" for="location" ng-show="errors.approved">
            @{{ errors.approved[0] }}
        </label>
    </div>
</div>

<div class="animated" ng-show="approved" ng-class="{ fadeIn: approved, fadeOut: !approved }">
    <div class="form-group" ng-show="preference_field">
        <label class="control-label col-lg-3 label_required">Tasa Preferencial: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <label class="radio-inline">
                    {!! Form::radio('preference_field', 1, false, [
                        'ng-model' => 'formData.preference_field'
                    ]) !!}
                    SI
                </label>
                <label class="radio-inline">
                    {!! Form::radio('preference_field', 0, false, [
                        'ng-model' => 'formData.preference_field',
                    ]) !!}
                    NO
                </label>
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.preference_field">
                @{{ errors.preference_field[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3 label_required">Tasa de Recargo: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <label class="radio-inline">
                    {!! Form::radio('surcharge', 1, false, [
                        'ng-click' => 'surcharge=true;',
                        'ng-model' => 'formData.surcharge'
                    ]) !!}
                    SI
                </label>
                <label class="radio-inline">
                    {!! Form::radio('surcharge', 0, false, [
                        'ng-click' => 'surcharge=false;',
                        'ng-model' => 'formData.surcharge'
                    ]) !!}
                    NO
                </label>
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.surcharge">
                @{{ errors.surcharge[0] }}
            </label>
        </div>
    </div>

    <div class="animated" ng-show="surcharge" ng-class="{ fadeIn: surcharge, fadeOut: !surcharge }">
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Porcentaje de Recargo: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user-plus"></i></span>
                    {!! Form::text('percentage', null, [
                        'class'        => 'form-control',
                        'placeholder'  => 'Porcentaje de Recargo',
                        'autocomplete' => 'off',
                        'ng-model'     => 'formData.percentage',
                        'ng-keyup'     => 'finalRate()',
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.percentage">
                    @{{ errors.percentage[0] }}
                </label>
            </div>
        </div>
        <span ng-show="percetage_extras">
            <div class="form-group">
                <label class="control-label col-lg-3 label_required">Extra Prima Banco: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">%</span>
                        {!! Form::text('percentage_bank', null, [
                            'class'        => 'form-control',
                            'placeholder'  => 'Extra Prima Banco',
                            'autocomplete' => 'off',
                            'ng-model'     => 'formData.percentage_bank',
                            'ng-keyup'     => 'finalRate()',
                            'readonly'     => true
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location"
                        ng-show="errors.percentage_bank">
                        @{{ errors.percentage_bank[0] }}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 label_required">Extra Prima CIA: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">%</span>
                        {!! Form::text('percentage_cia', null, [
                            'class'        => 'form-control',
                            'placeholder'  => 'Extra Prima Banco',
                            'autocomplete' => 'off',
                            'ng-model'     => 'formData.percentage_cia',
                            'ng-keyup'     => 'finalRate()',
                            'readonly'     => true
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label" for="location"
                        ng-show="errors.percentage_cia">
                        @{{ errors.percentage_cia[0] }}
                    </label>
                </div>
            </div>
        </span>
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Tasa Actual: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user-plus"></i></span>
                    {!! Form::text('current_rate', null, [
                        'class'        => 'form-control',
                        'placeholder'  => 'Tasa Actual',
                        'autocomplete' => 'off',
                        'ng-model'     => 'formData.current_rate',
                        'readonly'     => true
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.current_rate">
                    @{{ errors.current_rate[0] }}
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-lg-3 label_required">Tasa Recargo: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-user-plus"></i></span>
                {!! Form::text('final_rate', null, [
                    'class'            => 'form-control',
                    'placeholder'      => 'Tasa Recargo',
                    'autocomplete'     => 'off',
                    'ng-model'         => 'formData.final_rate',
                    'readonly'         => true
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.final_rate">
                @{{ errors.final_rate[0] }}
            </label>
        </div>
    </div>

</div>

<div class="animated" ng-show="approved" ng-class="{ fadeIn: approved, fadeOut: !approved }">
    <hr>
    <div class="alert alert-warning" ng-show="age > 65">
        El Cliente {{ $fa->detail->client->full_name }} sobrepasa los 65 años, por lo tanto no cuenta con la cobertura
        Invalidez Total Permanente.
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3 label_required">Coberturas: </label>
        <div class="col-lg-9">
            <div class="input-group">
                @foreach(config('base.client_coverages') as $key => $coverage)
                    <label class="radio-inline" style="display: {{ $key=='CF'?'none':'block'}}">
                        {!! Form::checkbox('coverages[' . $key .']', 1, false, [
                            'ng-model'    => 'formData.coverages.' . $key,
                            'ng-init'     => 'formData.coverages.' . $key . '=' . ($key === 'CTPD' ? '(age > 65 ? false : true)' : 'true'),
                            'ng-disabled' => ($key !== 'CTPD' ? 'true' : '(age > 65 ? true : false)'),
                        ]) !!}
                        {{ $coverage }}
                    </label><br>
                @endforeach
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.coverages">
                @{{ errors.coverages[0] }}
            </label>
        </div>
    </div>
    <hr>
</div>

<div class="animated" ng-show="extras">
    <div class="form-group" ng-show="current_rate_accidental">
        <label class="control-label col-lg-3 label_required">Tasa Actual: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-user-plus"></i></span>
                {!! Form::text('current_rate_accidental', null, [
                    'class'        => 'form-control',
                    'placeholder'  => 'Tasa Actual',
                    'autocomplete' => 'off',
                    'ng-model'     => 'formData.current_rate_accidental',
                    'readonly'     => true
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.current_rate_accidental">
                @{{ errors.current_rate_accidental[0] }}
            </label>
        </div>
    </div>
    <hr>
    <div class="alert alert-warning" ng-show="age > 65">
        El Cliente {{ $fa->detail->client->full_name }} sobrepasa los 65 años, por lo tanto no cuenta con la cobertura
        Invalidez Total Permanente.
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3 label_required">Coberturas: </label>
        <div class="col-lg-9">
            <div class="input-group">
                @foreach(config('base.client_coverages') as $key => $coverage)
                    <label class="radio-inline" style="display: {{ $key=='CF'?'none':'block'}}">
                        {!! Form::checkbox('coverages[' . $key .']', 1, false, [
                            'ng-model'    => 'formData.coverages.' . $key,
                            'ng-init'     => 'formData.coverages.' . $key . '=' . ($key === 'CTPD' ? '(age > 65 ? false : true)' : 'true'),
                            'ng-disabled' => ($key !== 'CTPD' ? 'true' : '(age > 65 ? true : false)'),
                        ]) !!}
                        {{ $coverage }}
                    </label><br>
                @endforeach
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.coverages">
                @{{ errors.coverages[0] }}
            </label>
        </div>
    </div>
    <hr>
</div>

<div class="animated" ng-show="state" ng-class="{ fadeIn: state, fadeOut: !state }">
    <div class="form-group">
        <label class="col-lg-3 control-label label_required">Estado: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <select ng-init="formData.state=currentOption"
                        ng-options="item as item.name for item in dataOptions track by item.id"
                        ng-model="currentOption" class="form-control" ng-change="stateChange()">
                </select>
            </div>
            <label id="location-error" class="validation-error-label" for="location" ng-show="errors.state">
                @{{ errors.state[0] }}
            </label>
        </div>
    </div>
</div>

<div class="form-group animated" ng-show="observation"
     ng-class="{ fadeIn: observation, fadeOut: !observation }">
    <label class="control-label col-lg-3 label_required">Observaciones: </label>
    <div class="col-lg-9">
        <div>
            {!! Form::textarea('observation', null, [
                'size'         => '4x4',
                'class'        => 'form-control',
                'placeholder'  => 'Observaciones',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.observation'
            ]) !!}
        </div>
        <label id="location-error" class="validation-error-label" for="location"
               ng-show="errors.observation">
            @{{ errors.observation[0] }}
        </label>
    </div>
</div>

@if(in_array($fa->detail->header->creditProduct->slug, ['PMO', 'PLT','PLNT','PLNT','PCA','PCM']))
    <div class="form-group" ng-class="{ fadeIn: observation, fadeOut: !observation }">
        <label class="control-label col-lg-3">Tarificación monetaria: </label>
        <div class="col-lg-9">
            <div>
                {!! Form::text('monetary_pricing', null, [
                    'class'        => 'form-control',
                    'placeholder'  => 'Tarificación monetaria',
                    'autocomplete' => 'off',
                    'ng-model'     => 'formData.monetary_pricing'
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.monetary_pricing">
                @{{ errors.monetary_pricing[0] }}
            </label>
        </div>
    </div>
@endif

@if(isset($product))
    @if($product === 'au')
        <label class="col-lg-3 control-label text-regular">Vehículo: </label>
        <span class="col-lg-9 control-label text-bold">{{ $fa->detail->vehicleType->vehicle }}</span>
        <br>
        <label class="col-lg-3 control-label text-regular">Marca: </label>
        <span class="col-lg-9 control-label text-bold">{{ $fa->detail->vehicleMake->make }}</span>
        <br>
        <label class="col-lg-3 control-label text-regular">Modelo: </label>
        <span class="col-lg-9 control-label text-bold">{{ $fa->detail->vehicleModel->model }}</span>
        <br>
        <label class="col-lg-3 control-label text-regular">Placa: </label>
        <span class="col-lg-9 control-label text-bold">{{ $fa->detail->license_plate }}</span>
    @endif
    @if($product === 'td')
        <label class="col-lg-3 control-label text-regular">Tipo de Inmueble: </label>
        <span class="col-lg-9 control-label text-bold">{{ config('base.property_types.' . $fa->detail->matter_insured) }}</span>
        <br>
        <label class="col-lg-3 control-label text-regular">Descripcion: </label>
        <span class="col-lg-9 control-label text-bold">{{ $fa->detail->matter_description }}</span>
    @endif
@endif

<div class="form-group">
    <label class="control-label col-lg-12 label_required">Envie la aprobación vía correo
        electrónico: </label>
    <br>
    <div class="col-lg-12">
        {!! Form::text('emails', null, [
            'class'        => 'form-control ',
            'placeholder'  => 'mail@email.com',
            'autocomplete' => 'off',
            'ng-model'     => 'formData.emails',
        ]) !!}
        <div class="input-group">
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.emails">
                @{{ errors.emails[0] }}
            </label>
        </div>
    </div>
</div>

<div class="text-right">
    <script ng-if="success.facultative">
        $(function () {
            messageAction('succes', 'El caso facultativo se proceso correctamente');
        });
    </script>

    <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cancelar
    </button>

    {!! Form::button('Guardar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
</div>
