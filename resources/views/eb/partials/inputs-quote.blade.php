<div>
    <h2>Datos del Cliente</h2>
    <div class="col-xs-12 col-md-6">
        <input type="hidden" name="code" value="{{ old('code', $client->code) }}">

        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Nombres: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    {!! Form::text('first_name', old('first_name', $client->first_name), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Nombres',
                        'autocomplete' => 'off',
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
                        'autocomplete' => 'off',
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
                        'autocomplete' => 'off',
                        (isset($issuance) ? 'readonly' : null)
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
                    'class' => 'select-search',
                ], old('extension', $client->extension)) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('extension') }}</label>
            </div>
        </div>

        @if(isset($issuance))
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
        @endif
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Género: </label>
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
            <label class="control-label col-lg-3 label_required">Lugar de nacimiento: </label>
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
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">
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
            <label class="col-lg-3 control-label">Teléfono de Oficina: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon">Cel.</span>
                    {!! Form::text('phone_number_office', old('phone_number_office', $client->phone_number_office), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Telefono de Oficina',
                        'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('phone_number_mobile') }}</label>
            </div>
        </div>

        @if(isset($issuance))
            <div class="form-group">
                <label class="control-label col-lg-3">
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
        @endif
    </div>

    <div class="clearfix"></div>

    <div class="col-xs-12 col-md-6">
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="text-right" style="margin: 10px 0;">
            {!! Form::button((isset($header_id) ? 'Actualizar' : 'Registrar') . ' datos del Cliente <i class="icon-arrow-right14 position-right"></i>', [
                'type'  => 'submit',
                'class' => 'btn btn-primary'
            ]) !!}
        </div>
    </div>
</div>