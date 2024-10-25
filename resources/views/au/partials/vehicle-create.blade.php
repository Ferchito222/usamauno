<input type="hidden" ng-model="formData.guarantee_id">
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Tipo de Vehículo: </label>
        <div class="col-lg-12 form-group">
            <select ng-options="t.vehicle for t in data.types track by t.id"
                    ng-model="formData.vehicle_type" class="form-control">
            </select>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['vehicle_type.id']">
                @{{ errors['vehicle_type.id'][0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Categoria: </label>
        <div class="col-lg-12">
            <select ng-options="c.category_name for c in data.categories track by c.id"
                    ng-model="formData.category" class="form-control" id="category">
            </select>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors['category.category']">
                @{{ errors['category.category'][0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Marca: </label>
        <div class="col-lg-12">
            {!! Form::text('brand', old('brand'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.brand',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.brand">
                @{{ errors.brand[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Modelo: </label>
        <div class="col-lg-12">
            {!! Form::text('model', old('model'), [
                'class'        => 'form-control',
                
                'ng-model'     => 'formData.model',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.model">
                @{{ errors.model[0] }}
            </label>
        </div>
    </div>
    <input type="hidden" name="brand_id" id="brand_id" ng-model="formData.brand_id">
    <div class="form-group">
        <label class="col-lg-3 control-label label_required">Año: </label>
        <div class="col-lg-9">
            <select name="year" class="select-search" ng-model="formData.year" id="year">
                <option value="">Seleccione</option>
                @for($i = date('Y')+1; $i >= date('Y') - $parameter->old_car; $i-- )
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
                <option value="old">-{{ $i + 1 }}</option>
            </select>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.year">
                @{{ errors.year[0] }}
            </label>
            {!! Form::text('year_old', old('year_old'), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => 'Año',
                'autocomplete' => 'off',
                'ng-model'     => 'formData.year_old',
                'ng-if'        => 'year_old',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.year_old">
                @{{ errors.year_old[0] }}
            </label>
        </div>
    </div>
    <div class="alert alert-danger" ng-if="year_old" style="padding: 5px; border: 1px solid;">
        <strong>Vehículo con antigüedad mayor a {{ $parameter->old_car }} años.</strong>
    </div>
    <hr />
    <div class="form-group">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon label_required">Placa </span>
                {!! Form::text('license_plate', old('license_plate'), [
                    'class'        => 'form-control ui-wizard-content',
                    'placeholder'  => 'Nº',
                    'autocomplete' => 'off',
                    'ng-model'     => 'formData.license_plate',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.license_plate">
                @{{ errors.license_plate[0] }}
            </label>
        </div>
    </div>
    <div class="alert alert-danger" style="padding: 5px; border: 1px solid;">
        <strong>Nota. En caso de que la placa este en tramite escriba ET</strong>
    </div>
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Uso de Vehículo: </label>
        <div class="col-lg-12">
            {!! SelectField::input('use', $data['vehicle_uses']->toArray(), [
                'class'    => 'form-control',
                'ng-model' => 'formData.use',
            ],
                old('use')) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.use">
                @{{ errors.use[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-9 control-label label_required">Cero Kilómetros: </label>
        <div class="col-lg-12">
            <select name="mileage" class="form-control" ng-model="formData.mileage">
                <option value="">Seleccione</option>
                <option value="1">SI</option>
                <option value="0">NO</option>
            </select>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.mileage">
                @{{ errors.mileage[0] }}
            </label>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Motor: </label>
        <div class="col-lg-12">
            {!! Form::text('engine', old('engine'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.engine',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.engine">
                @{{ errors.engine[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Chasis: </label>
        <div class="col-lg-12">
            {!! Form::text('chassis', old('chassis'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.chassis',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.chassis">
                @{{ errors.chassis[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Color: </label>
        <div class="col-lg-12">
            {!! Form::text('color', old('color'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.color',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.color">
                @{{ errors.color[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Capacidad de Tonelaje: </label>
        <div class="col-lg-12">
            {!! Form::text('tonnage_capacity', old('tonnage_capacity'), [
                'class'        => 'form-control',
                'ng-model'     => 'formData.tonnage_capacity',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.tonnage_capacity">
                @{{ errors.tonnage_capacity[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 ">Nro de asientos: </label>
        <div class="col-lg-12">
            {!! Form::text('seat_number', old('seat_number'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.seat_number',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.seat_number">
                @{{ errors.seat_number[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 ">Plaza de cirtulación: </label>
        <div class="col-lg-12">
            {!! Form::text('circulation_plaza', old('circulation_plaza'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.circulation_plaza',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.circulation_plaza">
                @{{ errors.circulation_plaza[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 ">Desplazamiento: </label>
        <div class="col-lg-12">
            {!! Form::text('displacement', old('displacement'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.displacement',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.displacement">
                @{{ errors.displacement[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 ">Origen: </label>
        <div class="col-lg-12">
            {!! Form::text('origin', old('origin'), [
                'class'        => 'form-control',
                'readonly'=>1,
                'ng-model'     => 'formData.origin',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                ng-show="errors.origin">
                @{{ errors.origin[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Valor Comercial
            ({{ $detail->currency }}): </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{ $detail->currency }}</span>
                {!! Form::text('insured_value', old('insured_value'), [
                    'class'        => 'form-control',
                    'autocomplete' => 'off',
                    'autocomplete' => 'off',
                    'id' => 'insured_value',
                    'placeholder'  => '(' . $detail->currency . ')',
                    'ng-model'     => 'formData.insured_value',
                    
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.insured_value">
                @{{ errors.insured_value[0] }}
            </label>
        </div>
    </div>
    <div class="form-group" style="display: none;">
        <label class="col-lg-9 control-label label_required">Seguro con Daños Propios: </label>
        <div class="col-lg-12">
            <label class="radio-inline">
                {!! Form::radio('own_damage_insurance', 1, false, [
                    'ng-click'   => 'own_damage_insurance = true; ',
                    'ng-model' => 'formData.own_damage_insurance',
                    'ng-checked' => (is_null($detail->ad_vehicle_type_id) || $detail->own_damage_insurance==true)?'false':'true',
                ]) !!}
                SI
            </label>
            <label class="radio-inline">
                {!! Form::radio('own_damage_insurance', 0, false, [
                    'ng-click'   => 'own_damage_insurance = false; ',
                    'ng-model'   => 'formData.own_damage_insurance',
                    'ng-checked' => !is_null($detail->ad_vehicle_type_id) && $detail->own_damage_insurance==false?'false':'true',
                ]) !!}
                NO
            </label>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.own_damage_insurance">
                @{{ errors.own_damage_insurance[0] }}
            </label>
        </div>
    </div>
   
    <input type="hidden" name="currency" ng-model="formData.currency">
    <div class="alert alert-danger " ng-if="insured_value_ca" style="padding: 5px; border: 1px solid;">
        <strong>
            El Veículo excedió el monto máximo permitido 
            {{ number_format($parameter->amount_max_ca, 2) }} USD, consulte con la aseguradora.
        </strong>
        <br />
        <strong>Nota:</strong> En exceso de estos montos, se requiere la gestión de parte del corredor de seguros, para la autorización correspondiente de la Compañía Aseguradora.
    </div>
    <div class="alert alert-danger " ng-if="insured_value_cb" style="padding: 5px; border: 1px solid;">
        <strong>
            El Veículo excedió el monto máximo permitido 
            {{ number_format($parameter->amount_max_cb, 2) }} USD, consulte con la aseguradora.
        </strong>
        <br />
        <strong>Nota:</strong> En exceso de estos montos, se requiere la gestión de parte del corredor de seguros, para la autorización correspondiente de la Compañía Aseguradora.
    </div>
</div>
<div class="col-md-12 col-xs-12">
    <div class="form-group" style="display: none;">
        <div class="alert alert-success text-justify">
            <strong>Cobertura Completa:</strong> Incluye cobertura de Daños Propios, tasa anual 3,2%<br />
            <strong>Cobertura Parcial:</strong> NO Incluye cobertura de Daños Propios, tasa anual 1,7%<br />
            <br />
            <strong>Definición de Cobertura de Daños Propios: </strong> 
            “Cubre cualquier daño material causado en el vehículo asegurado por un 
            siniestro, ya sea ocasionado por el mismo asegurado o una tercera persona”
        </div>
    </div>
    <div class="alert alert-primary" style="padding: 5px; border: 1px solid;">
        <strong>Nota.</strong> En caso de no contar con algún dato del VEHICULO favor completarla en el <strong>NetBank</strong>
        <br />
        e intente nuevamente.
    </div>
</div>