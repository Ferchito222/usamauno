@if(! isset($_GET['idf']))
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
                    'readonly' => 1,
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
    </div>
    <div class="col-xs-12 col-md-6">
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
        <div class="alert alert-danger no-border" ng-if="year_old">
            Vehículos con antigüedad mayor a {{ $parameter->old_car }} años requieren aprobación de la compañia de
            seguros.
        </div>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon label_required">Placa </span>
                    {!! Form::text('license_plate', old('license_plate'), [
                        'class'        => 'form-control ui-wizard-content',
                        'placeholder'  => 'Nº',
                        'autocomplete' => 'off',
                        'readonly' => 1,
                        'ng-model'     => 'formData.license_plate',
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.license_plate">
                    @{{ errors.license_plate[0] }}
                </label>
            </div>
        </div>
        <div class="alert alert-danger no-border">
            <span class="text-semibold">Nota.</span> En caso de que la placa este en tramite escriba
            <a href="#" class="alert-link">ET</a>.
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

    <div class="clearfix"></div>
    <hr>
@endif

<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon label_required">Color </span>
                {!! Form::text('color', old('color'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'readonly' => 1,
                    'ng-model'     => 'formData.color',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.color">
                @{{ errors.color[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon label_required">Motor </span>
                {!! Form::text('engine', old('engine'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'readonly' => 1,
                    'ng-model'     => 'formData.engine',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.engine">
                @{{ errors.engine[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon label_required">Chasis </span>
                {!! Form::text('chassis', old('chassis'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'readonly' => 1,
                    'ng-model'     => 'formData.chassis',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.chassis">
                @{{ errors.chassis[0] }}
            </label>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">Cap/Ton</span>
                {!! Form::text('tonnage_capacity', old('tonnage_capacity'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'ng-model'     => 'formData.tonnage_capacity',
                    'readonly'     => 1,
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.tonnage_capacity">
                @{{ errors.tonnage_capacity[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">N° Asientos</span>
                {!! Form::text('seat_number', old('seat_number'), [
                    'class'        => 'form-control ui-wizard-content',
                    'autocomplete' => 'off',
                    'readonly' => 1,
                    'ng-model'     => 'formData.seat_number',
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.seat_number">
                @{{ errors.seat_number[0] }}
            </label>
        </div>
    </div>

    @if(request()->has('coverage'))
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Valor Comercial
                ({{ $header->currency }}): </label>
            <div class="col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class=" icon-coin-dollar"></i></span>
                    {!! Form::text('insured_value', old('insured_value'), [
                        'class'        => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder'  => '(' . $header->currency . ')',
                        'ng-model'     => 'formData.insured_value',
                    ]) !!}
                </div>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.insured_value">
                    @{{ errors.insured_value[0] }}
                </label>
            </div>
        </div>
        <div class="alert alert-danger no-border" ng-if="insured_value">
            Vehículos cuyo valor excedan los {{ number_format($parameter->amount_max, 2) }} USD requieren
            aprobación de la compañia de seguros.
        </div>
    @else
        <div class="form-group">
            <div class="col-lg-12">
                <div class="alert alert-info text-bold no-padding-left no-padding-right text-center"
                     role="alert">@{{ data.insured_value | currency:'Valor Comercial (' + currency + ') ' }}</div>
                <div class="alert alert-info text-bold no-padding-left no-padding-right text-center"
                     role="alert">@{{ data.premium | currency:'Prima (' + currency + ') ' }}</div>
            </div>
        </div>
    @endif
</div>

<div class="clearfix"></div>