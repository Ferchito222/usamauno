@if($form == 'create')
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Código Catastral: </label>
            <div class="col-lg-12 form-group">
                {!! Form::text('nro_folio_real', old('nro_folio_real'), [
                'class'        => 'form-control',
                'ng-model'     => 'formData.nro_folio_real',
                ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.nro_folio_real">
                    @{{ errors.nro_folio_real[0] }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-9 control-label label_required">Materia: </label>
            <div class="col-lg-12 form-group">
                <select ng-options="t.name for t in data.property_types track by t.id"
                        ng-model="formData.matter_insured" class="form-control" id="matter_insured">
                </select>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors['matter_insured.id']">
                    @{{ errors['matter_insured.id'][0] }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-9 control-label label_required">Descripción: </label>
            <div class="col-lg-12 form-group">
                    {!! Form::textarea('matter_description', old('matter_description'), [
                        'class'        => 'form-control ui-wizard-content',
                        'size'         => '4x4',
                        'ng-model'     => 'formData.matter_description'
                    ]) !!}
                    <label id="location-error" class="validation-error-label" for="location"
                           ng-show="errors.matter_description">
                        @{{ errors.matter_description[0] }}
                    </label>
                </label>
            </div>
        </div>
        <div class="form-group number_field">
            <label class="col-lg-9 control-label label_required">Número de Serie: </label>
            <div class="col-lg-12 form-group">
                    {!! Form::text('number', old('number'), [
                        'class'        => 'form-control ui-wizard-content',
                        'id'           => 'number',
                        'ng-model'     => 'formData.number'
                    ]) !!}
                    <label id="location-error" class="validation-error-label" for="location"
                           ng-show="errors.number">
                        @{{ errors.number[0] }}
                    </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-9 control-label label_required">Uso: </label>
            <div class="col-lg-12 form-group">
                <select ng-options="t.name for t in data.property_uses track by t.id"
                        ng-model="formData.use" class="form-control" id="use">
                </select>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors['use.id']">
                    @{{ errors['use.id'][0] }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-9 control-label label_required">Estado: </label>
            <div class="col-lg-12 form-group">
                <select ng-options="t.name for t in data.property_states track by t.id"
                        ng-model="formData.state" class="form-control" id="state">
                </select>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors['state.id']">
                    @{{ errors['state.id'][0] }}
                </label>
                <div class="alert-danger small" ng-if="state_remotelation" style="padding: 5px; border: 1px solid;">
                <strong>
                    Elija esta opción solo si el destino del crédito es para la remodelación del Inmueble y el mismo cuenta con un Plan de Inversión.
                </strong>
                </div>
                <div class="alert-danger small" ng-if="state_construction" style="padding: 5px; border: 1px solid;">
                <strong>
                    Elija esta opción solo si a la fecha la construcción se encuentra en obra.
                </strong>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Zona: </label>
            <div class="col-lg-12 form-group">
                {!! Form::text('zone', old('zone'), [
                'class'        => 'form-control',
                'ng-model'     => 'formData.zone',
                ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.zone">
                    @{{ errors.zone[0] }}
                </label>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Localidad: </label>
            <div class="col-lg-12 form-group">
                {!! Form::text('locality', old('locality'), [
                'class'        => 'form-control',
                'ng-model'     => 'formData.locality',
                ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.locality">
                    @{{ errors.locality[0] }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Dirección: </label>
            <div class="col-lg-12 form-group">
                {!! Form::textarea('address', old('address'), [
                'class'        => 'form-control',
                'size' => '4x4',
                'ng-model'     => 'formData.address',
                ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.address">
                    @{{ errors.address[0] }}
                </label>
            </div>
        </div>
        <div class="form-group construct">
            <label class="control-label col-lg-9 label_required">Valor de Reposición ({{ $header->currency }}): </label>
            <div class="col-lg-12 form-group">
                    {!! Form::text('construction_value', old('construction_value'), [
                        'class'        => 'form-control',
                        'id'           => 'construction_value',
                        'ng-model'     => 'formData.construction_value',
                    ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.construction_value">
                    @{{ errors.construction_value[0] }}
                </label>
            </div>
            <br />
            <small class="help-block">No Considerar el Valor del Terreno.</small>
        </div>
        <div class="form-group construct">
            <label class="control-label col-lg-9 label_required">Monto del desembolso para la Remodelación ({{ $header->currency }}): </label>
            <div class="col-lg-12 form-group">
                    {!! Form::text('remodeling_value', old('remodeling_value'), [
                        'class'        => 'form-control',
                        'id'           => 'remodeling_value',
                        'ng-model'     => 'formData.remodeling_value',
                    ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.remodeling_value">
                    @{{ errors.remodeling_value[0] }}
                </label>
            </div>
        </div>
        <div class="form-group insured">
            <label class="control-label col-lg-9 label_required">Valor de Reposición ({{ $detail->currency }}): </label>
            <div class="col-lg-12 form-group">
                    {!! Form::text('insured_value', old('insured_value'), [
                        'class'        => 'form-control',
                        'id'           => 'insured_value',
                        'ng-model'     => 'formData.insured_value',
                    ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.insured_value">
                    @{{ errors.insured_value[0] }}
                </label>
            </div>
        </div>
        <input type="hidden" name="currency" ng-model="formData.currency">
        <div class="alert alert-danger no-border" ng-if="insured_value">
            Riesgos cuyo valor excedan los 
            <span id="monto_riesgo" style="display: none">{{ number_format($parameter->amount_max, 2) }}</span> 
            <span id="monto_industrial">{{'200.000'}}</span> USD requieren aprobación de la
            compañia de seguros.
        </div>
        <div class="form-group construction_term_block" ng-if="construction_term">
            <label class="control-label col-lg-9 label_required">Plazo Final de la Construcción (Meses): </label>
            <div class="col-lg-12 form-group">
                    {!! Form::text('construction_term', old('construction_term'), [
                        'class'        => 'form-control',
                        'id'           => 'construction_term',
                        'ng-model'     => 'formData.construction_term',
                    ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.construction_term">
                    @{{ errors.construction_term[0] }}
                </label>
                <div class="alert-info small">
                    <strong>Ingrese </strong> la cantidad de meses que le tomara para la construcción<br />
                </div>
            </div>
        </div>
        <div class="alert alert-danger " ng-if="insured_riesgo" style="padding: 5px; border: 1px solid;">
            <strong>
                El Riesgo excedió el monto máximo permitido "Monto maximo permitido  
                {{ number_format($parameter->amount_max, 2) }} USD".
            </strong>
            <br />
                <strong>Nota:</strong> En exceso de estos montos, se requiere la gestión de parte del corredor de seguros, para la autorización correspondiente de la Compañía Aseguradora.
        </div>
        
        <div class="form-group">
            <label class="col-lg-9 control-label label_required">Departamento: </label>
            <div class="col-lg-12 form-group">
                <select ng-options="t.name for t in data.cities track by t.id"
                        ng-model="formData.city" class="form-control">
                </select>
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors['city.id']">
                    @{{ errors['city.id'][0] }}
                </label>
            </div>
        </div>
        <div class="alert alert-danger no-border" ng-if="insured_industrial">
            Inmuebles cuyo uso sea tipo INDUSTRIAL requiere aprobacion de la compañia, favor comunicarse con Omar Gonzalez.
        </div>
        <span style="display: none;">
        {!! Form::text('currency_value', old('currency_value'), [          
                        'id'           => 'currency_value',
                        'ng-model'     => 'formData.currency_value',
                    ]) !!}
        {!! Form::text('exchange', old('exchange'), [
                        'id'           => 'exchange',
                        'ng-model'     => 'formData.exchange',
                    ]) !!}
        </span>
    </div>
@else
<div class="col-xs-12 col-md-6">
    @if($detail->matter_insured != 'PR')
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Marca: </label>
            <div class="col-lg-12 form-group">
                {!! Form::text('make', old('make'), [
                'class'        => 'form-control',
                'ng-model'     => 'formData.make',
                ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.make">
                    @{{ errors.make[0] }}
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Modelo: </label>
            <div class="col-lg-12 form-group">
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
        <div class="form-group">
            <label class="control-label col-lg-9 label_required">Número de chasis: </label>
            <div class="col-lg-12 form-group">
                {!! Form::text('chassis_number', old('chassis_number'), [
                'class'        => 'form-control',
                'ng-model'     => 'formData.chassis_number',
                ]) !!}
                <label id="location-error" class="validation-error-label" for="location"
                       ng-show="errors.chassis_number">
                    @{{ errors.chassis_number[0] }}
                </label>
            </div>
        </div>
    @endif
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Zona: </label>
        <div class="col-lg-12 form-group">
            {!! Form::text('zone', old('zone'), [
            'class'        => 'form-control',
            'ng-model'     => 'formData.zone',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.zone">
                @{{ errors.zone[0] }}
            </label>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-6">
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Localidad: </label>
        <div class="col-lg-12 form-group">
            {!! Form::text('locality', old('locality'), [
            'class'        => 'form-control',
            'ng-model'     => 'formData.locality',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.locality">
                @{{ errors.locality[0] }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-9 label_required">Dirección: </label>
        <div class="col-lg-12 form-group">
            {!! Form::textarea('address', old('address'), [
            'class'        => 'form-control',
            'size' => '4x4',
            'ng-model'     => 'formData.address',
            ]) !!}
            <label id="location-error" class="validation-error-label" for="location"
                   ng-show="errors.address">
                @{{ errors.address[0] }}
            </label>
        </div>
    </div>
    {!! Form::hidden('matter_insured', old('matter_insured'), [

            'ng-model'     => 'formData.matter_insured',
            ]) !!}
</div>
@endif