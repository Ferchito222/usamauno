@foreach($details as $key => $detail)
    <h3 class="label label-primary">Titular {{ $key + 1 }}</h3>
    <div class="row">
        <input type="hidden" name="clients[{{ $key }}][id]" value="{{ encode($detail->client->id) }}">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Nombres</label>
                <input type="text" class="form-control disabled-client" placeholder="Nombres"
                       name="clients[{{ $key }}][first_name]" value="{{ $detail->client->first_name }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Ap. Paterno</label>
                <input type="text" class="form-control disabled-client" placeholder="Ap. Paterno"
                       name="clients[{{ $key }}][last_name]" value="{{ $detail->client->last_name }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Ap. Materno</label>
                <input type="text" class="form-control disabled-client" placeholder="Ap. Materno"
                       name="clients[{{ $key }}][mother_last_name]" value="{{ $detail->client->mother_last_name }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Ap. de casada</label>
                <input type="text" class="form-control disabled-client" placeholder="Ap. de casada"
                       name="clients[{{ $key }}][married_name]" value="{{ $detail->client->married_name }}"
                       autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Tipo de documento</label>
                {!! SelectField::input('clients[' . $key . '][document_type]', $data['document_type']->toArray(), [
                    'class' => 'client',
                    ], old('document_type', $detail->client->document_type))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Documento de identidad</label>
                <input type="text" class="form-control disabled-client" placeholder="Documento de identidad"
                       name="clients[{{ $key }}][dni]" value="{{ $detail->client->dni }}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Complemento</label>
                <input type="text" class="form-control disabled-client" placeholder="Complemento"
                       name="clients[{{ $key }}][complement]" value="{{ $detail->client->complement }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Ext. Documento de identidad</label>
                {!! SelectField::input('clients[' . $key . '][extension]', $data['cities']['CI']->toArray(), [
                    'class' => 'form-control disabled-client',
                    ], old('extension', $detail->client->extension))
                !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Estado Civil</label>
                {!! SelectField::input('clients[' . $key . '][civil_status]', $data['civil_status']->toArray(), [
                    'class' => 'form-control disabled-client',
                    ], old('civil_status', $detail->client->civil_status))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">País</label>
                <input type="text" class="form-control disabled-client" placeholder="País"
                       name="clients[{{ $key }}][country]" value="{{ $detail->client->country }}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Fecha de nacimiento</label>
                <input type="date" class="form-control disabled-client" placeholder="Fecha de nacimiento"
                       name="clients[{{ $key }}][birthdate]" value="{{ $detail->client->birthdate->format('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Lugar de nacimiento</label>
                <input type="text" class="form-control disabled-client" placeholder="Lugar de nacimiento"
                       name="clients[{{ $key }}][birth_place]" value="{{ $detail->client->birth_place }}"
                       autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Lugar de residencia</label>
                {!! SelectField::input('clients[' . $key . '][place_residence]', $data['cities']['DE']->toArray(), [
                    'class' => 'client',
                    ], old('place_residence', $detail->client->place_residence))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Localidad</label>
                <input type="text" class="form-control disabled-client" placeholder="Localidad"
                       name="clients[{{ $key }}][locality]" value="{{ $detail->client->locality }}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Avenida o Calle</label>
                {!! SelectField::input('clients[' . $key . '][avenue_street]', $data['avenue_street']->toArray(), [
                    'class' => 'form-control disabled-client',
                    ], old('avenue_street', $detail->client->avenue_street))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Dirección</label>
                <textarea class="form-control disabled-client" placeholder="Dirección"
                          name="clients[{{ $key }}][home_address]" cols="4"
                          rows="3">{{ $detail->client->home_address }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Número de domicilio</label>
                <input type="text" class="form-control disabled-client" placeholder="Número de domicilio"
                       name="clients[{{ $key }}][home_number]" value="{{ $detail->client->home_number }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Dirección laboral</label>
                <textarea class="form-control disabled-client" placeholder="Dirección laboral"
                          name="clients[{{ $key }}][business_address]" cols="4"
                          rows="3">{{ $detail->client->business_address }}</textarea>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Ocupación (CAEDEC)</label>
                {!! SelectField::input('clients[' . $key . '][ad_activity_id]', $data['activities']->toArray(), [
                    'class' => 'select-search disabled-client',
                    ], old('ad_activity_id', $detail->client->ad_activity_id))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Descripción ocupación</label>
                <textarea class="form-control disabled-client" placeholder="Descripción ocupación"
                          name="clients[{{ $key }}][occupation_description]" cols="4"
                          rows="3">{{ $detail->client->occupation_description }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Telefono 1</label>
                <input type="text" class="form-control disabled-client" placeholder="Telefono 1"
                       name="clients[{{ $key }}][phone_number_home]" value="{{ $detail->client->phone_number_home }}"
                       autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Telefono 2</label>
                <input type="text" class="form-control disabled-client" placeholder="Telefono 2"
                       name="clients[{{ $key }}][phone_number_mobile]"
                       value="{{ $detail->client->phone_number_mobile }}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Telef. Oficina</label>
                <input type="text" class="form-control disabled-client" placeholder="Telef. Oficina"
                       name="clients[{{ $key }}][phone_number_office]"
                       value="{{ $detail->client->phone_number_office }}" autocomplete="off">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Correo electónico</label>
                <input type="text" class="form-control disabled-client" placeholder="Correo electónico"
                       name="clients[{{ $key }}][email]" value="{{ $detail->client->email }}" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Peso</label>
                <div class="input-group">
                    <input type="text" class="form-control disabled-client" placeholder="Peso"
                           name="clients[{{ $key }}][weight]" value="{{ $detail->client->weight }}" autocomplete="off">
                    <div class="input-group-addon">kg.</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Estatura</label>
                <div class="input-group">
                    <input type="text" class="form-control disabled-client" placeholder="Estatura"
                           name="clients[{{ $key }}][height]" value="{{ $detail->client->height }}" autocomplete="off">
                    <div class="input-group-addon">cm.</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Género</label>
                {!! SelectField::input('clients[' . $key . '][gender]', $data['gender']->toArray(), [
                    'class' => 'form-control disabled-client',
                    ], old('gender', $detail->client->gender))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Mano utilizada para escribir y/o firmar</label>
                {!! SelectField::input('clients[' . $key . '][hand]', $data['hands']->toArray(), [
                    'class' => 'form-control disabled-client',
                    ], old('hand', $detail->client->hand))
                !!}
            </div>
        </div>
    </div>
    <hr>
@endforeach