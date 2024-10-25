<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Materia: </label>
        <div class="col-lg-12 form-group">
            {!! SelectField::input('matter_insured', $data['property_types']->toArray(), [
                'class'    => 'form-control',
                'ng-model' => 'formData.matter_insured',
            ], old('matter_insured')) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['matter_insured']">
                @{{ errors['matter_insured'][0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Descripción: </label>
        <div class="col-lg-12">
            {!! Form::textarea('matter_description', old('matter_description'), [
                'size'         => '4x4',
                'class'        => 'form-control',
                'placeholder'  => 'Descripción',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.matter_description',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['matter_description']">
                @{{ errors['matter_description'][0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-12 control-label label_required">Número: </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">Nro</span>
                {!! Form::text('number', old('number'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Número',
                    'ng-model'     => 'formData.number',
                ]) !!}
            </div>
            <small>N° Folio Real o N° Serie Según corresponda</small>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['number']">
                @{{ errors['number'][0] }}
            </label>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Uso: </label>
        <div class="col-lg-12 form-group">
            {!! SelectField::input('property_use', $data['property_uses']->toArray(), [
                'class'    => 'form-control',
                'ng-model' => 'formData.property_use',
                'id'       => 'property_use',
            ], old('property_use')) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['property_use']">
                @{{ errors['property_use'][0] }}
            </label>
        </div>
    </div>

    <h6>Valor Asegurado</h6>
    <hr/>

    <div class="form-group valor_asegurado">
        <label class="control-label col-lg-12 label_required" id="insured_value_label">Valor Asegurado: </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{ $header->currency }}</span>
                {!! Form::text('insured_value', old('insured_value'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Valor Asegurado',
                    'ng-model'     => 'formData.insured_value',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['insured_value']">
                @{{ errors['insured_value'][0] }}
            </label>
        </div>
    </div>

    <div class="form-group valor_de_terreno" ng-if="property">
        <label class="control-label col-lg-12 label_required">Valor del Terreno: </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{ $header->currency }}</span>
                {!! Form::text('land_value', old('land_value'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Valor del Terreno',
                    'ng-model'     => 'formData.land_value',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['land_value']">
                @{{ errors['land_value'][0] }}
            </label>
        </div>
    </div>
</div>

<div class="clearfix"></div>
<hr>

<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Departamento: </label>
        <div class="col-lg-12 form-group">
            {!! SelectField::input('city', $data['cities']['DE']->toArray(), [
                'class'    => 'form-control',
                'ng-model' => 'formData.city',
            ], old('city')) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['city']">
                @{{ errors['city'][0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Zona: </label>
        <div class="col-lg-12">
            {!! Form::textarea('zone', old('zone'), [
                'size'         => '4x4',
                'class'        => 'form-control',
                'placeholder'  => 'Zona',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.zone',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['zone']">
                @{{ errors['zone'][0] }}
            </label>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Localidad: </label>
        <div class="col-lg-12">
            {!! Form::text('locality', old('locality'), [
                'size'         => '4x4',
                'class'        => 'form-control',
                'placeholder'  => 'Localidad',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.locality',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['locality']">
                @{{ errors['locality'][0] }}
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Dirección: </label>
        <div class="col-lg-12">
            {!! Form::textarea('address', old('address'), [
                'size'         => '4x4',
                'class'        => 'form-control',
                'placeholder'  => 'Dirección',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.address',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['address']">
                @{{ errors['address'][0] }}
            </label>
        </div>
    </div>

    @if(request()->has('coverage'))
    @else
    @endif
</div>

<div class="clearfix"></div>