@foreach($risks as $key => $detail_risk)
<h3 class="label label-primary">{{$code}} {{ $key + 1 }}</h3>
    <div class="row">
        <input type="hidden" name="risks[{{ $key }}][id]" value="{{ encode($detail_risk->id) }}">
        
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Codigo Catastral:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Codigo Catastral"
                       name="risks[{{ $key }}][nro_folio_real]" value="{{ $detail_risk->nro_folio_real }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Localidad:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Localidad"
                       name="risks[{{ $key }}][locality]" value="{{ $detail_risk->locality }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Materia:</label>
                {!! SelectField::input('risks[' . $key . '][matter_insured]', $data['property_types']->toArray(), [
                    'class' => 'form-control disabled-risk',
                    ], old('matter_insured', $detail_risk->matter_insured))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Dirección:</label>
                {!! Form::textarea('risks[' . $key . '][address]', old('address', $detail_risk->address), [
                        'class'        => 'form-control disabled-risk',
                        'size'         => '4x4'
                    ]) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Descripción:</label>
                {!! Form::textarea('risks[' . $key . '][matter_description]', old('matter_description', $detail_risk->matter_description), [
                        'class'        => 'form-control disabled-risk',
                        'size'         => '4x4'
                    ]) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Valor de reposicion:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Valor de reposicion"
                       name="risks[{{ $key }}][insured_value]" value="{{ $detail_risk->insured_value }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Uso:</label>
                {!! SelectField::input('risks[' . $key . '][use]', $data['property_uses']->toArray(), [
                    'class' => 'form-control disabled-risk',
                    ], old('use', $detail_risk->use))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Departamento:</label>
                {!! SelectField::input('risks[' . $key . '][city]', $data['cities']['DE']->toArray(), [
                    'class' => 'form-control disabled-risk',
                    ], old('city', $detail_risk->city))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Estado:</label>
                {!! SelectField::input('risks[' . $key . '][state]', $data['property_states']->toArray(), [
                    'class' => 'form-control disabled-risk',
                    ], old('use', $detail_risk->state))
                !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Zona:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Zona"
                       name="risks[{{ $key }}][zone]" value="{{ $detail_risk->zone }}">
            </div>
        </div>
    </div>
    <hr>
@endforeach