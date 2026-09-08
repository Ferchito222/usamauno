<div class="col-xs-12 col-md-6">
    <input type="hidden" value="{{ old('code', $client->code) }}" name="code">

    @if(strpos(request()->route()->getName(), 'edit') === false)
        <input type="hidden" value="{{ old('debit_balance', (empty($client->debit_balance) ? 0 : $client->debit_balance)) }}" name="debit_balance">
        <input type="hidden" value="{{ old('number', empty($client->number) ? 0 : $client->number) }}" name="number">
        <input type="hidden" value="{{ old('addresses', empty($client->addresses) ? json_encode([]) : $client->addresses) }}" name="addresses">
        <input type="hidden" value="{{ old('operations', empty($client->operations) ? json_encode([]) : $client->operations) }}" name="operations">
    @endif

    <div class="form-group">
        <label class="control-label col-lg-3 label_required">Nombres: </label>
        <div class="col-lg-9">
            {!! Form::text('first_name', old('first_name', (request()->session()->has('guest') && request()->route()->getName() === 'de.detail.create' ? request()->session()->get('guest')->first_name : $client->first_name)), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => 'Nombres',
                'autocomplete' => 'off',
            ]) !!}
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('first_name') }}</label>
        </div>
    </div>

    @if($errors->has('any_last_name'))
        <div class="col-lg-3"></div>
        <div class="col-lg-9">
            <label class="validation-error-label">
                Uno de los apellidos es requerido.
            </label>
        </div>
    @endif

    <div class="form-group">
        <label class="control-label col-lg-3 label_required">Ap. Paterno: </label>
        <div class="col-lg-9">
            {!! Form::text('last_name', old('last_name', (request()->session()->has('guest') && request()->route()->getName() === 'de.detail.create' ? request()->session()->get('guest')->last_name : $client->last_name)), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => 'Ap. Paterno',
                'autocomplete' => 'off',
            ]) !!}
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('last_name') }}</label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3">Ap. Materno: </label>
        <div class="col-lg-9">
            {!! Form::text('mother_last_name', old('mother_last_name', $client->mother_last_name), [
                'class' => 'form-control',
                'placeholder' => 'Ap. Materno',
                'autocomplete' => 'off'])
            !!}
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('mother_last_name') }}</label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3">Ap. de casada: </label>
        <div class="col-lg-9">
            {!! Form::text('married_name', old('married_name', $client->married_name), [
                'class' => 'form-control',
                'placeholder' => 'Ap. de casada',
                'autocomplete' => 'off'])
            !!}
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('married_name') }}</label>
        </div>
    </div>
    @if(strpos(request()->route()->getName(), 'de.') === false)
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
    @endif
    @if(isset($product) && $product === 'td')
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Documento de identidad: </label>
            <div class="col-lg-9">
                {!! Form::text('dni', old('dni', $client->dni), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'Documento de identidad',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('dni') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Ext. Documento de identidad: </label>
            <div class="col-lg-9">
                {!! SelectField::input('extension', $data['cities']['CI']->toArray(), [
                'class' => 'select-search'],
                old('extension', $client->extension)) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('extension') }}</label>
            </div>
        </div>
    @endif
    @if(! isset($ref) && ! isset($product))
        @if(strpos(request()->route()->getName(), 'de.') === false)
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
        @endif
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Documento de identidad: </label>
            <div class="col-lg-9">
                <input type="hidden" name="document_type_bank" value="{{ old('document_type_bank', $client->document_type_bank) }}">
                {!! Form::text('dni', old('dni', $client->dni), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'Documento de identidad',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('dni') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">Complemento: </label>
            <div class="col-lg-9">
                {!! Form::text('complement', old('complement', $client->complement), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'Complemento',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('complement') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Ext. Documento de identidad: </label>
            <div class="col-lg-9">
                {!! SelectField::input('extension', $data['cities']['CI']->toArray(), [
                    'class' => 'select-search'],
                    old('extension', $client->extension)) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('extension') }}</label>
            </div>
        </div>
    @endif
    @if(strpos(request()->route()->getName(), 'de.') === false)
        <div class="form-group">
            <label class="control-label col-lg-3 {{ (isset($product) && ($product === 'au'|| $product === 'td')) ? '' : 'label_required' }}">País: </label>
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
    @endif
    <div class="form-group">
        <label class="control-label col-lg-3 label_required">Fecha de nacimiento: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                {!! Form::text('birthdate', old('birthdate', (empty($client->birthdate) ? '' : dateToFormat($client->birthdate))), [
                    'class' => 'form-control pickadate-cobodate',
                    'autocomplete' => 'off',
                    'placeholder' => 'Seleccione fecha'])
                !!}
            </div>
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('birthdate') }}</label>
        </div>
    </div>
    @if(! isset($product))
        @if(strpos(request()->route()->getName(), 'de.') === false)
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
        @endif
    @endif
    <div class="form-group">
        <label class="control-label col-lg-3 ">Lugar de nacimiento: </label>
        <div class="col-lg-9">
            {!! Form::text('birth_place', old('birth_place', $client->birth_place), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => 'Lugar de nacimiento',
                'autocomplete' => 'off',
            ]) !!}
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('birth_place') }}</label>
        </div>
    </div>

    @if(auth()->user()->profile->first()->slug !== 'GUP')
        <div class="form-group">
            <label class="control-label col-lg-3 ">Localidad: </label>
            <div class="col-lg-9">
                {!! Form::text('locality', old('locality', $client->locality), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'Localidad',
                    'autocomplete' => 'off'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('locality') }}</label>
            </div>
        </div>
        @include('partials.fields', ['type'=>'combo2','label'=>'Género','id'=>'gender','required'=>1,'value'=>$client->gender,'array'=>$data['gender']->toArray()])
        @include('partials.fields', ['type'=>'combo', 'label'=>'Estado Civil', 'id'=>'civil_status', 'required'=>1,'value'=>$client->civil_status,'array'=>$data['civil_status']->toArray()])    
    @endif

    @if(isset($ref))
        @if(strpos(request()->route()->getName(), 'de.') === false)
            <div class="form-group">
                <label class="col-lg-3 control-label label_required">Mano utilizada para escribir y/o firmar: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        {!! SelectField::input('hand', $data['hands']->toArray(), [
                            'class' => 'select-search'],
                            old('hand', $detail->client->hand))
                        !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('hand') }}</label>
                </div>
            </div>
        @endif
    @endif
</div>
<div class="col-xs-12 col-md-6">
    @if(isset($product) && $product === 'td')
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
    @if(isset($ref))
        @if(strpos(request()->route()->getName(), 'de.') === false)
            <div class="form-group">
                <label class="col-lg-3 control-label label_required">Avenida o Calle: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-user"></i></span>
                        {!! SelectField::input('avenue_street', $data['avenue_street']->toArray(), [
                            'class' => 'select-search'],
                            old('avenue_street', $detail->client->avenue_street))
                        !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('avenue_street') }}</label>
                </div>
            </div>
        @endif
    @endif

    @if(auth()->user()->profile->first()->slug !== 'GUP')
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Dirección: </label>
            <div class="col-lg-9">
                {!! Form::textarea('home_address', old('home_address', $client->home_address), [
                    'size'         => '4x4',
                    'class'        => 'form-control',
                    'placeholder'  => 'Dirección',
                    'autocomplete' => 'off',
                ]) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('home_address') }}</label>
            </div>
        </div>
    @endif

    @if(isset($ref) || (isset($product) && ($product === 'au' || $product === 'td')))
        @if(strpos(request()->route()->getName(), 'de.') === false)
            <div class="form-group">
                <label class="control-label label_required col-lg-3">Número de domicilio: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">Nro.</span>
                        {!! Form::text('home_number', old('home_number', (
                        isset($product) && ($product === 'au' || $product === 'td') ? $client->home_number : $detail->client->home_number)), [
                            'class' => 'form-control ui-wizard-content',
                            'autocomplete' => 'off',
                            'placeholder' => 'Número de domicilio'])
                        !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('home_number') }}</label>
                </div>
            </div>
        @endif
    @endif

    @if(auth()->user()->profile->first()->slug !== 'GUP')
        <div class="form-group">
            <label class="control-label col-lg-3">Dirección laboral: </label>
            <div class="col-lg-9">
                {!! Form::textarea('business_address', old('business_address', $client->business_address), [
                    'size'         => '4x4',
                    'class'        => 'form-control',
                    'placeholder'  => 'Dirección laboral',
                    'autocomplete' => 'off',
                ]) !!}
                <label class="validation-error-label">{{ $errors->first('business_address') }}</label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-lg-3">Lugar de Trabajo: </label>
            <div class="col-lg-9">
                {!! Form::text('workplace', old('workplace', $client->workplace), [
                    'class'        => 'form-control ui-wizard-content',
                    'placeholder'  => 'Lugar de Trabajo',
                    'autocomplete' => 'off',
                ]) !!}
                <label class="validation-error-label">{{ $errors->first('workplace') }}</label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label label_required">Ocupación (CAEDEC): </label>
            <div class="col-lg-9">
                {!! SelectField::input('ad_activity_id', $data['activities']->toArray(), [
                    'class' => 'select-search'],
                    old('ad_activity_id', $client->ad_activity_id)) !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('ad_activity_id') }}</label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Descripción de actividad: </label>
            <div class="col-lg-9">
                {!! Form::textarea('occupation_description', old('occupation_description', $client->occupation_description), [
                    'size' => '4x4',
                    'class' => 'form-control',
                    'placeholder' => 'Descripción ocupación'])
                !!}
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('occupation_description') }}</label>
            </div>
        </div>
    @endif

    <div class="form-group">
        <label class="control-label col-lg-3 label_required">{{ (isset($product) && $product === 'td') ? 'Teléfono de domicilio' : 'Telefono 1' }}: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-phone"></i></span>
                {!! Form::text('phone_number_home', old('phone_number_home', $client->phone_number_home), [
                    'class' => 'form-control ui-wizard-content',
                    'placeholder' => 'Telefono 1',
                    'autocomplete' => 'off',
                    'id' => 'phone_number_home',
                    'maxlength' => 8])
                !!}
            </div>
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('phone_number_home') }}</label>
        </div>
    </div>
    <script type="text/javascript">
        $('#phone_number_home').on('input', function () {
            var digits = $(this).val().replace(/\D/g, '');

            if (digits.length && ! /^[567234]/.test(digits)) {
                digits = digits.substring(1);
            }

            $(this).val(digits);
        });
    </script>

    @if(auth()->user()->profile->first()->slug !== 'GUP')
        <div class="form-group">
            <label class="control-label col-lg-3">{{ (isset($product) && $product === 'td') ? 'Teléfono celular' : 'Telefono 2' }}: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-phone"></i></span>
                    {!! Form::text('phone_number_mobile', old('phone_number_mobile', $client->phone_number_mobile), [
                        'id' => 'phone_number_mobile',
                        'class' => 'form-control ui-wizard-content',
                        'placeholder' => 'Telefono 2',
                        'autocomplete' => 'off',
                        'maxlength' => 8])
                    !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('phone_number_mobile') }}</label>
            </div>
        </div>
        <script type="text/javascript">
            $('#phone_number_mobile').on('input', function () {
                var digits = $(this).val().replace(/\D/g, '');

                if (digits.length && ! /^[567234]/.test(digits)) {
                    digits = digits.substring(1);
                }

                $(this).val(digits);
            });
        </script>
    @endif
    @if(! isset($product))
        @if(auth()->user()->profile->first()->slug !== 'GUP')
            <div class="form-group">
                <label class="control-label col-lg-3">Telef. Oficina: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-phone"></i></span>
                        {!! Form::text('phone_number_office', old('phone_number_office', $client->phone_number_office), [
                            'class' => 'form-control ui-wizard-content',
                            'placeholder' => 'Telef. Oficina',
                            'autocomplete' => 'off',
                            'id' => 'phone_number_office',
                            'maxlength' => 8])
                        !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('phone_number_office') }}</label>
                </div>
            </div>
        @endif
    @else
        @if($product === 'td')
            <div class="form-group">
                <label class="control-label col-lg-3">Telef. Oficina: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-phone"></i></span>
                        {!! Form::text('phone_number_office', old('phone_number_office', $client->phone_number_office), [
                            'class' => 'form-control ui-wizard-content',
                            'placeholder' => 'Telef. Oficina',
                            'autocomplete' => 'off'])
                        !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('phone_number_office') }}</label>
                </div>
            </div>
        @endif
    @endif
    <script type="text/javascript">
        $('#phone_number_office').on('input', function () {
            var digits = $(this).val().replace(/\D/g, '');

            if (digits.length && ! /^[567234]/.test(digits)) {
                digits = digits.substring(1);
            }

            $(this).val(digits);
        });
    </script>
    <div class="form-group">
        <label class="control-label col-lg-3">Correo electónico: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-mail5"></i></span>
                {!! Form::email('email', old('email', (request()->session()->has('guest') && request()->route()->getName() === 'de.detail.create' ? request()->session()->get('guest')->email : $client->email)), [
                    'class'        => 'form-control ui-wizard-content',
                    'placeholder'  => 'mail@email.com',
                    'autocomplete' => 'off',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first('email') }}</label>
        </div>
    </div>
    @if(! isset($ref))
        @if(! isset($product))
            <div class="form-group">
                <label class="control-label col-lg-3 label_required">Peso: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">(Kg)</span>
                        {!! Form::text('weight', old('weight', $client->weight), [
                          'id'           => 'weight',
                          'class'        => 'form-control ui-wizard-content',
                          'placeholder'  => 'Peso',
                          'autocomplete' => 'off'
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('weight') }}</label>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 label_required">Estatura: </label>
                <div class="col-lg-9">
                    <div class="input-group">
                        <span class="input-group-addon">(cm)</span>
                        {!! Form::text('height', old('height', $client->height), [
                          'id'           => 'height',
                          'class'        => 'form-control ui-wizard-content',
                          'placeholder'  => 'Estatura',
                          'autocomplete' => 'off'
                        ]) !!}
                    </div>
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('height') }}</label>
                </div>
            </div>
        @endif

        @if(strpos(request()->route()->getName(), 'de.') === false)
            <div class="form-group">
                <label class="col-lg-3 control-label label_required">Género: </label>
                <div class="col-lg-9">
                    {!! SelectField::input('gender', $data['gender']->toArray(), [
                        'class' => 'select-search'],
                        old('gender', $client->gender)) !!}
                    <label id="location-error" class="validation-error-label"
                           for="location">{{ $errors->first('gender') }}</label>
                </div>
            </div>
        @endif
    @endif

    @if($header->coverage->slug === 'CO')
        <div class="form-group">
            <label class="control-label col-lg-3 label_required">Participación: </label>
            <div class="col-lg-9">
                <div class="input-group">
                    <span class="input-group-addon">%</span>
                    {!! Form::text('percentage_credit', old('percentage_credit', $detail->percentage_credit), [
                      'id'           => 'percentage_credit',
                      'class'        => 'form-control ui-wizard-content',
                      'placeholder'  => 'Porcentaje de participación',
                      'autocomplete' => 'off'
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('percentage_credit') }}</label>
            </div>
        </div>
    @endif

    @if($header->creditProduct->slug === '7x5' && auth()->user()->profile->first()->slug !== 'GUP')
        <hr>
        <div class="form-group">
            <label class="col-lg-9 control-label label_required">
                Cobertura de Desempleo Involuntario:
                <i class="icon-question7" data-toggle="tooltip" data-placement="right" data-html="true" data-container="body"
                   title="Cobertura de Desempleo Involuntario"></i>
            </label>
            <div class="col-lg-9">
                <div class="radio">
                    <label class="radio-inline">
                        <input type="radio" name="involuntary_unemployment"
                               value="1" {{ (! is_null(old('involuntary_unemployment')) && old('involuntary_unemployment') == 1) ? 'checked' : ((! is_null($detail->involuntary_unemployment) && $detail->involuntary_unemployment) ? 'checked' : 'checked') }}> SI
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="involuntary_unemployment"
                               value="0" {{ (! is_null(old('involuntary_unemployment')) && old('involuntary_unemployment') == 0) ? 'checked' : ((! is_null($detail->involuntary_unemployment) && ! $detail->involuntary_unemployment) ? 'checked' : '') }}> NO
                    </label>
                </div>
                <label id="location-error" class="validation-error-label"
                       for="location">{{ $errors->first('involuntary_unemployment') }}</label>
            </div>
        </div>

        <script type="text/javascript">
            $('[data-toggle="tooltip"]').tooltip();
        </script>
    @endif

    <input type="hidden" name="coverage" value="{{ $header->coverage->slug }}">
    <input type="hidden" name="credit_product" value="{{ $header->creditProduct->slug }}">
</div>

@if(auth()->user()->profile->first()->slug !== 'GUP')

    @include('partials.fields', ['type'=>'hidden', 'id'=>'economic_activity_type_code', 'required'=>0,'value'=>$client->economic_activity_type_code])    
    @include('partials.fields', ['type'=>'hidden', 'id'=>'economic_activity_type', 'required'=>0,'value'=>$client->economic_activity_type])    
    @include('partials.fields', ['type'=>'hidden', 'id'=>'detail_operations', 'required'=>0,'value'=>$detail->detail_operations])    
    {{-- <span style="display: {{ (old('economic_activity_type_code', $client->economic_activity_type_code)==2 && $header->creditProduct->slug != 'PCA')?'block':'none' }}"> --}}
    <span>
        {{-- <div class="col-xs-12 col-md-12">
            <legend class="text-bold" ng-init="workdata({{ old('cesantia', $detail->cesantia) }})">Cobertura de Desempleo - cliente {{ old('economic_activity_type',$client->economic_activity_type) }}</legend>
            <small class="help-block">IMPORTANTE! LA PRESENTE COBERTURA UNICAMENTE SERA APLICABLE PARA CLIENTES EN SITUACION LABORAL DE DEPENDIENTE INDEFINIDA</small>
        </div> --}}
        <div class="col-xs-12 col-md-12">
            <legend class="text-bold" ng-init="workdata({{ old('cesantia', $detail->cesantia) }})">Cobertura de Desempleo</legend>
            <small class="help-block">IMPORTANTE! LA PRESENTE COBERTURA UNICAMENTE SERA APLICABLE PARA CLIENTES EN SITUACION LABORAL DE DEPENDIENTE INDEFINIDA</small>
        </div>
        <div class="col-xs-12 col-md-12">
            @include('partials.fields', ['type'=>'radio', 'label'=>'Desea Cobertura de Desempleo?', 'id'=>'cesantia', 'required'=>1,'value'=>(string)old('cesantia', $detail->cesantia), 'array'=>[['id'=>1, 'name'=>'Si'], ['id'=>0, 'name'=>'No']]])    
        </div>
        <div class="col-xs-12 col-md-6 data_work" style="display: none">            
            @include('partials.fields', ['type'=>'text', 'label'=>'Empresa donde Trabaja', 'id'=>'company_work', 'required'=>1,'value'=>$client->company_work])    
            {{-- @include('partials.fields', ['type'=>'text', 'label'=>'Año de ingreso', 'id'=>'year_of_entry_to_work', 'required'=>1,'value'=>$client->year_of_entry_to_work])     --}}
            @include('partials.fields', ['type'=>'date','label'=>'Fecha de ingreso','id'=>'date_entry','required'=>1,'value'=>$detail->date_entry])
        </div>
        <div class="col-xs-12 col-md-6 data_work" style="display: none">            
            @include('partials.fields', ['type'=>'combo2', 'label'=>'Tipo de Contrato', 'id'=>'type_of_contract', 'required'=>1,'value'=>$client->type_of_contract,'array'=>$data['type_of_contracts']->toArray()])    
        </div>
    </span>
    <div class="col-xs-12 col-md-6" style="display: none">
        <span class="data_work" style="display: none">
            @include('partials.fields', ['type'=>'combo2', 'label'=>'Ocupo algun cargo publico?', 'id'=>'public_work', 'required'=>0,'value'=>$client->public_work, 'array'=>[['id'=>0, 'name'=>'No'],['id'=>1, 'name'=>'Si']]])    
            @include('partials.fields', ['type'=>'text', 'label'=>'Cargo', 'id'=>'position', 'required'=>0,'value'=>$client->position])    
            @include('partials.fields', ['type'=>'text', 'label'=>'Periodo', 'id'=>'period', 'required'=>0,'value'=>$client->period])    
            @include('partials.fields', ['type'=>'text', 'label'=>'Institución', 'id'=>'institution', 'required'=>0,'value'=>$client->institution])    
        </span>
    </div>
    <div class="col-xs-12 col-md-12 alert alert-danger alert_plazo_fijo" style="display: none">
        LA COBERTURA DE DESEMPLEO UNICAMENTE APLICA PARA CLIENTES QUE CUENTEN CON EL TIPO DE CONTRATO <strong>INDEFINIDO</strong>.
    </div>
@endif