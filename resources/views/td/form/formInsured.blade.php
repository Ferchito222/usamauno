{!! Form::open(array('route' => ['td.save.insured','rp_id'=>$rp_id,'header_id'=>encode($header_id)], 'name' => 'Form', 'id' => 'insured_form', 'class'=>'form-horizontal')) !!}
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-12 control-label label_required">Materia: </label>
        <div class="col-lg-12">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
            <input type="hidden" value="{{ $header_id }}" name="id_header" id="id_header">
            <input type="hidden" value="{{ $detail->id }}" name="id_detail" id="id_detail">
            {!! SelectField::input('matter_insured', $materia, [
                'class' => 'select2-choice col-xs-12 col-lg-12',
                'id' => 'matter_insured'],
                old('matter_insured', $detail->matter_insured))
            !!}
        </div>
        <small class="matter_insured_msg" style="color: red;"></small>
    </div>
    <div class="form-group">
        <label class="col-lg-12 control-label label_required">Descripción: </label>
        <div class="col-lg-12">
            {!! Form::textarea('matter_description', old('matter_description', $detail->matter_description), [
                'size'         => '4x4',
                'class'        => 'form-control',
                'placeholder'  => 'Descripción',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.reason',
                'id'     => 'matter_description'])
            !!}
        </div>
        <small class="matter_description_msg" style="color: red;"></small>
    </div>
    <div class="form-group">
        <label class="col-lg-12 control-label label_required">Número: </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">Nro</span>
                {!! Form::text('number', old('number', $detail->number), [
                    'class'        => 'form-control ui-wizard-content',
                    'id' => 'number',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Número'])
                !!}
            </div>
            <small class="number_msg" style="color: red;"></small><br />
            <small>N° Folio Real o N° Serie Según corresponda</small>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-12 control-labe label_required">Uso: </label>
        <div class="col-lg-12">
            {!! SelectField::input('use', $uso, [
                'class' => 'select2-choice col-xs-12 col-lg-12',
                'id' => 'use'],
                old('use', $detail->use))
            !!}
        </div>
        <small class="use_msg" style="color: red;"></small><br />
        <small>Domiciliario / Industrial u Otro</small>
    </div>
    <h6>Valor Asegurado</h6>
    <hr/>
    <div class="form-group valor_construccion" style="display: none;">
        <label class="control-label col-lg-12 label_required">Valor Construcción: </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{ $header->currency }}</span>
                {!! Form::text('construction_value', old('construction_value', $detail->insured_value), [
                    'class'        => 'form-control ui-wizard-content',
                    'id'        => 'construction_value',
                    'operation_number'        => 'operation_number',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Valor Construcción'])
                !!}
            </div>
            <small class="construction_value_msg" style="color: red;"></small>
        </div>
    </div>
   
    <div class="form-group valor_asegurado">
        <label class="control-label col-lg-12 label_required">Valor Asegurado: </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{ $header->currency }}</span>
                {!! Form::text('insured_value', old('insured_value', $detail->insured_value), [
                    'class'        => 'form-control ui-wizard-content',
                    'id'        => 'insured_value',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Valor Asegurado'])
                !!}
            </div>
            <small class="insured_value_msg" style="color: red;"></small>
        </div>
    </div>
</div>

<div class="col-xs-12 col-md-12"></div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-12 control-label">Departamento: </label>
        <div class="col-lg-12">
            {!! SelectField::input('city', $city, [
                'class' => 'select2-choice col-xs-12 col-lg-12',
                'id' => 'city'],
                old('city', $detail->city))
            !!}
        </div>
        <small class="city_msg" style="color: red;"></small>
    </div>
    <div class="form-group">
        <label class="col-lg-12 control-label">Zona: </label>
        <div class="col-lg-12">
            {!! Form::textarea('zone', old('zone',$detail->zone), [
                'size'         => '4x4',
                'class'        => 'form-control',
                'id'        => 'zone',
                'placeholder'  => 'Zona',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.reason'
            ]) !!}
        </div>
        <small class="zone_msg" style="color: red;"></small>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-12 control-label label_required">Ciudad o Localidad: </label>
        <div class="col-lg-12">
            {!! Form::text('locality', old('locality', $detail->locality), [
                'class'        => 'form-control ui-wizard-content',
                'id'        => 'locality',
                'autocomplete' => 'off',
                'placeholder'  => 'Ciudad o Localidad'])
            !!}
        </div>
        <div class="col-lg-12">
            <small class="locality_msg" style="color: red;"></small>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-12 control-label">Dirección: </label>
        <div class="col-lg-12">
            {!! Form::textarea('address', old('address', $detail->address), [
                'size'         => '4x4',
                'class'        => 'form-control',
                'id'        => 'address',
                'placeholder'  => 'Dirección',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.reason'
            ]) !!}
        </div>
        <small class="address_msg" style="color: red;"></small>
    </div>
</div>
<div class="clearfix"></div>
<div class="text-right">
    <button type="submit" class="btn btn-primary">Guardar <i class="glyphicon glyphicon-floppy-disk position-right"></i>
    </button>
    <a style="display:none;" class="list_content"
       onclick="listInsured('{{ route('td.list.insured',['rp_id'=>$rp_id,'header_id'=>encode($header_id), 'steep'=>$steep])}}', 'GET', '{{ $header_id }}');"></a>

    @if($steep == 3)
        <script>
            function reloadPage(){
                location.reload();
            }
        </script>
    @else
        <script>
            function reloadPage(){
                $('.list_content').click();
            }
        </script>    
    @endif
</div>
{!!Form::close()!!}
{!! Html::script('js/validate_form_insured.js') !!}
