@if(! isset($_GET['idf']))
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="control-label col-lg-9">Uso</label>
            <div class="col-lg-12">
                    {!! Form::text('property_use', old('property_use'), [
                        'class'        => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder'  => 'Uso',
                        'ng-model'     => 'formData.property_use',
                    ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.property_use">
                    @{{ errors.property_use[0] }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-9 control-label label_required">Descripción: </label>
            <div class="col-lg-12">
                {!! Form::textarea('description', old('description'), [
                    'size'         => '4x4',
                    'class'        => 'form-control',
                    'placeholder'  => 'Descripción',
                    'autocomplete' => 'off',
                    'ng-model'     => 'formData.description',
                ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.description">
                    @{{ errors.description[0] }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Número de Serie</label>
            <div class="col-lg-12">
                    {!! Form::text('serial_number', old('serial_number'), [
                        'class'        => 'form-control',
                        'autocomplete' => 'off',
                        'readonly'     => 1,
                        'placeholder'  => 'Número de Serie',
                        'ng-model'     => 'formData.serial_number',
                    ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.serial_number">
                    @{{ errors.serial_number[0] }}
                </label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
        <label class="control-label col-lg-9 label_required">Ciudad</label>
        <div class="col-lg-12">
                {!! Form::text('city', old('city'), [
                    'class'        => 'form-control',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Ciudad',
                    'ng-model'     => 'formData.city',
                ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.city">
                @{{ errors.city[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Valor Comercial
            ({{ $header->currency }}): </label>
        <div class="col-lg-12">
            <div class="input-group">
                <span class="input-group-addon"><i class=" icon-coin-dollar"></i></span>
                {!! Form::text('insured_value', old('insured_value'), [
                    'class'        => 'form-control',
                    'autocomplete' => 'off',
                    'readonly' => 1,
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
@endif