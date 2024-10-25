@if(! isset($issuance))
    @if(! isset($header_id))
        <input type="hidden" name="is_client" value="{{ old('is_client') }}">
        <input type="hidden" name="s_dni" value="{{ old('s_dni') }}">
        <input type="hidden" name="s_extension" value="{{ old('s_extension') }}">
    @endif

    <input type="hidden" name="client_eco" id="client_eco"
           value="{{ old('client_eco', (isset($header_id) ? ($header->detail->client_eco ? encode($client->id) : '') : (old('is_client') == 1 ? encode($client->id) : ''))) }}">

    <input type="hidden" name="account[type]" value="{{ old('account.type', $account['type']) }}">
    <input type="hidden" name="account[currency]"
           value="{{ old('account.currency', $account['currency']) }}">
    <input type="hidden" name="account[number]" value="{{ old('account.number', $account['number']) }}">
    <input type="hidden" name="account[total]" value="{{ old('account.total', $account['total']) }}">

    @if($errors->has('account.type') || $errors->has('account.currency')
            || $errors->has('account.number') || $errors->has('account.total'))
        <label id="location-error" class="validation-error-label"
               for="location">El Cliente no tiene una cuenta registrada.</label>
    @endif
@endif

<div>
    <h2>Datos del Cliente</h2>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Nombres: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    {!! Form::text('first_name', old('first_name', $client->first_name), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Nombres',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('first_name') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Apellido Paterno: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    {!! Form::text('last_name', old('last_name', $client->last_name), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Ap. Paterno',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('last_name') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Apellido Materno: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    {!! Form::text('mother_last_name', old('mother_last_name', $client->mother_last_name), [
                        'class'        => 'form-control',
                        'placeholder'  => 'Ap. Materno',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('mother_last_name') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Apellido de Casada: </label>
            <div class="col-lg-9">
                {!! Form::text('married_name', old('married_name', $client->married_name), [
                    'class'        => 'form-control',
                    'placeholder'  => 'Ap. de casada',
                    'autocomplete' => 'off'
                ]) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('married_name') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Estado Civil: </label>
            <div class="col-lg-9">
                {!! SelectField::input('civil_status', $data['civil_status']->toArray(), [
                    'class' => 'select-search'],
                    old('civil_status', $client->civil_status)) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('civil_status') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Tipo de documento:</label>
            <div class="col-lg-9">
                {!! SelectField::input('document_type', $data['document_type']->toArray(), [
                    'class' => 'select-search'],
                    old('document_type', $client->document_type)) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('document_type') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Documento de Identidad: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon">Nº</span>
                    {!! Form::text('dni', old('dni', $client->dni), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Documento de identidad',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('dni') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Complemento: </label>
            <div class="col-lg-9">
                {!! Form::text('complement', old('complement', $client->complement), [
                    'class'        => 'form-control ui-wizard-content',
                    'placeholder'  => 'Complemento',
                    'autocomplete' => 'off'
                ]) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('complement') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Extensión: </label>
            <div class="col-lg-9">
                {!! SelectField::input('extension', $data['cities']['CI']->toArray(), [
                    'class' => 'select-search'],
                    old('extension', $client->extension))
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('extension') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 {{ isset($issuance) ? 'label_required' : '' }}">País: </label>
            <div class="col-lg-9">
                {!! Form::text('country', old('country', $client->country), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'País',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('country') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label {{ isset($issuance) ? 'label_required' : '' }}">Género: </label>
            <div class="col-lg-9">
                {!! SelectField::input('gender', $data['gender']->toArray(), [
                    'class' => 'select-search'],
                    old('gender', $client->gender))
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('gender') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Fecha de nacimiento: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                    {!! Form::text('birthdate', old('birthdate', dateToFormat($client->birthdate)), [
                        'class'        => 'form-control pickadate-cobodate',
                        'autocomplete' => 'off',
                        'placeholder'  => 'Seleccione fecha'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('birthdate') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Lugar de nacimiento: </label>
            <div class="col-lg-9">
                {!! Form::text('birth_place', old('birth_place', $client->birth_place), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'Lugar de nacimiento',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('birth_place') }}</label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label {{ isset($issuance) ? 'label_required' : '' }}">
                Lugar de residencia:
            </label>
            <div class="col-lg-9">
                {!! SelectField::input('place_residence', $data['cities']['DE']->toArray(), [
                    'class' => 'select-search'],
                    old('place_residence', $client->place_residence)) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('place_residence') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 {{ isset($issuance) ? 'label_required' : '' }}">Barrio / Zona: </label>
            <div class="col-lg-9">
                {!! Form::text('locality', old('locality', $client->locality), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'Barrio / Zona',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('locality') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 {{ isset($issuance) ? 'label_required' : '' }}">
                Dirección de domicilio:
            </label>
            <div class="col-lg-9">
                {!! Form::textarea('home_address', old('home_address', $client->home_address), [
                    'size' => '4x4',
                    'class' => 'form-control',
                    'placeholder' => 'Dirección',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('home_address') }}</label>
            </div>
        </div>
        @if(isset($issuance))
            <div class="form-group">
                <label class="control-label label_required col-lg-3">Número de domicilio: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">Nro.</span>
                        {!! Form::text('home_number', old('home_number', $client->home_number), [
                            'class' => 'form-control ui-wizard-content',
                            'autocomplete' => 'off',
                            'placeholder' => 'Número de domicilio'])
                        !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('home_number') }}</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label label_required">Avenida o Calle: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        {!! SelectField::input('avenue_street', $data['avenue_street']->toArray(), [
                            'class' => 'select-search'],
                            old('avenue_street', $client->avenue_street))
                        !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('avenue_street') }}</label>
                </div>
            </div>
        @endif
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Ocupación: </label>
            <div class="col-lg-9">
                {!! SelectField::input('ad_activity_id', $data['activities']->toArray(), [
                    'class' => 'select-search'],
                    old('ad_activity_id', $client->ad_activity_id))
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('ad_activity_id') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 {{ isset($issuance) ? 'label_required' : '' }}">
                Descripción Ocupación:
            </label>
            <div class="col-lg-9">
                {!! Form::textarea('occupation_description', old('occupation_description', $client->occupation_description), [
                    'size'        => '4x4',
                    'class'       => 'form-control',
                    'placeholder' => 'Descripción ocupación'
                ]) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('occupation_description') }}</label>
            </div>
        </div>
        @if(isset($issuance))
            <div class="form-group">
                <label class="control-label col-lg-3 label_required">Dirección laboral: </label>
                <div class="col-lg-9">
                    {!! Form::textarea('business_address', old('business_address', $client->business_address), [
                        'size'         => '4x4',
                        'class'        => 'form-control',
                        'placeholder'  => 'Dirección laboral',
                        'autocomplete' => 'off'
                    ]) !!}
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('business_address') }}</label>
                </div>
            </div>
        @endif
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Teléfono de domicilio: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-phone"></i></span>
                    {!! Form::text('phone_number_home', old('phone_number_home', $client->phone_number_home), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Telefono de domicilio',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('phone_number_home') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Teléfono celular: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon">Cel.</span>
                    {!! Form::text('phone_number_mobile', old('phone_number_mobile', $client->phone_number_mobile), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Telefono celular',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('phone_number_mobile') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Email: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon">@</span>
                    {!! Form::email('email', old('email', $client->email), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'mail@email.com',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('email') }}</label>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-12">
        <hr/>
    </div>
    <div class="clearfix"></div>

    <div class="col-xs-12 col-md-6">
        @if(! isset($issuance))
            <div class="form-group">
                <label class="col-lg-3 control-label label_required">Referido por: </label>
                <div class="col-lg-9">
                    <select name="reference_id" id="reference" class="select-search">
                        @foreach($data['cashiers'] as $cashier)
                            <option value="{{ $cashier->slug }}"
                                    {{ old('reference_id', (isset($header_id) && ! is_null($header->detail->reference) ? $header->detail->reference->slug : '')) === $cashier->slug ? 'selected' : '' }}>
                                {{ $cashier->full_name }}
                            </option>
                        @endforeach
                        <option value="other" {{ old('reference_id', (isset($header_id) ? (is_null($header->detail->reference) ? 'other' : '') : '')) === 'other' ? 'selected' : '' }}>
                            Otro
                        </option>
                    </select>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('reference_id') }}</label>
                </div>
            </div>
            <div class="form-group" id="reference_other" style="display: none;">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-9">
                    {!! Form::text('reference_other', old('reference_other', (isset($header_id) ? (is_null($header->detail->reference) ? $header->detail->reference_other : '') : '')), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Oficial de Crédito',
                        'autocomplete' => 'off'
                    ]) !!}
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('reference_other') }}</label>
                </div>
            </div>
        @endif
    </div>
    <div class="col-xs-12 col-md-6">
        @if(! isset($issuance))
            <div class="form-group" id="code"
                 style="{{ isset($header_id) ? ($header->detail->client_eco ? '' : 'display: none;') : 'display: none;' }}">
                <label class="control-label col-lg-4 label_required">Código de Agenda: </label>
                <div class="col-lg-8">
                    {!! Form::text('code', old('code', $client->code), [
                        'class'          => 'form-control ui-wizard-content',
                        'placeholder'    => 'Código de Agenda',
                        'autocomplete'   => 'off',
                        'readonly'       => true,
                        'data-toggle'    => 'tooltip',
                        'data-placement' => 'top',
                        'title'          => 'Si es cliente de Ecofuturo, realice la busquda de datos del cliente para que el código de agenda se visualice en este campo.'
                    ]) !!}
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('code') }}</label>
                </div>
            </div>

            <div class="text-right" style="margin: 10px 0;">
                @if(request()->route()->getName() === 'elp.edit')
                    <a href="{{ route('elp.lists', ['rp_id' => $rp_id, 'header_id' => $header_id ]) }}"
                       class="btn border-slate text-slate-800 btn-flat">Cancelar</a>
                @endif

                {!! Form::button((isset($header_id) ? 'Actualizar' : 'Registrar') . ' datos del Cliente <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
            </div>
        @else
            <div class="text-right" style="margin: 10px 0;">
                <a href="{{ route('elp.edit.issuance', ['rp_id' => $rp_id, 'header_id' => $header_id ]) }}"
                   class="btn border-slate text-slate-800 btn-flat">Cancelar</a>

                <button type="submit" class="btn btn-primary">Actualizar información
                    <i class="icon-arrow-right14 position-right"></i>
                </button>
            </div>
        @endif
    </div>
</div>