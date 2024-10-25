@foreach($risks as $key => $detail_risk)

<h3 class="label label-primary">{{$code}} {{ $key + 1 }}</h3>
    <div class="row">
        <input type="hidden" name="risks[{{ $key }}][id]" value="{{ encode($detail_risk->id) }}">
        
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Uso:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Uso"
                       name="risks[{{ $key }}][property_use]" value="{{ $detail_risk->property_use }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Ciudad:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Ciudad"
                       name="risks[{{ $key }}][city]" value="{{ $detail_risk->city }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Descripción:</label>
                {!! Form::textarea('risks[' . $key . '][description]', old('description', $detail_risk->description), [
                        'class'        => 'form-control disabled-risk',
                        'size'         => '4x4'
                    ]) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Valor Comercial:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Valor Comercial"
                       name="risks[{{ $key }}][insured_value]" value="{{ $detail_risk->insured_value }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group text-center">
                <label class="label label-default">Nro de Serie:</label>
                <input type="text" class="form-control disabled-risk" placeholder="Nro de Serie"
                       name="risks[{{ $key }}][serial_number]" value="{{ $detail_risk->serial_number }}">
            </div>
        </div>
    </div>
    <hr>
@endforeach