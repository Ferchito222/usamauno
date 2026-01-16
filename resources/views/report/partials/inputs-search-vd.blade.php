<div class="form-group" ng-controller="ReportController">
    @if(request()->route()->getParameter('type') === 'GEN')
        <label class="label label-info" style="font-size: 100%;">Aprobado</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[issued_gen][1]" {{ old('cc1.issued_gen.1') == 1 ? 'checked' : '' }}>
                Emitido
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[issued_gen][0]" {{ old('cc1.issued_gen.0') == 1 ? 'checked' : '' }}>
                No Emitido
            </label>
        </div>
        <br>

        <label class="label label-info" style="font-size: 100%;">Anulado</label>
        <div>
            <label class="checkbox-inline checkbox-right" style="margin-left: 100px;">
                <strong>Anulado:</strong>
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[canceled_gen][1]" {{ old('cc1.canceled_gen.1') == 1 ? 'checked' : '' }}> SI
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[canceled_gen][0]" {{ old('cc1.canceled_gen.0') == 1 ? 'checked' : '' }}> NO
            </label>
        </div>
        <br>
    @elseif(request()->route()->getParameter('type') === 'ISS')
        <label class="label label-info" style="font-size: 100%;">Anulado</label>
        <div>
            <label class="checkbox-inline checkbox-right">
                <strong>Anulado:</strong>
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[canceled][1]" {{ old('cc1.canceled.1') == 1 ? 'checked' : '' }}> SI
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[canceled][0]" {{ old('cc1.canceled.0') == 1 ? 'checked' : '' }}> NO
            </label>
        </div>
    @elseif(request()->route()->getParameter('type') === 'COLL')
        <label class="label label-info" style="font-size: 100%;">ESTADO</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox"
                       name="charged" ng-model="charged" {{ old('charged') == 1 ? 'checked' : '' }}> PAGADO
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="valid" {{ old('valid') == 1 ? 'checked' : '' }}> VIGENTE
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="not_valid" {{ old('not_valid') == 1 ? 'checked' : '' }}> NO VIGENTE
            </label>
            <label class="checkbox-inline">
                <input type="checkbox"
                       name="debt" ng-model="debt" {{ old('debt') == 1 ? 'checked' : '' }}> MORA
            </label>
        </div><br>

        <div id="transaction_date" ng-hide="chargedDate">
            <div class="col-xs-4">
                Fecha Cobro (Desde - Hasta)
                {!! Form::text('date_begin', old('date_begin'), [
                    'class'        => 'form-control pickadate-cobodate',
                    'placeholder'  => 'dd/mm/yyyy',
                    'autocomplete' => 'off'
                ]) !!}

            </div>

            <div class="col-xs-4">
                :
                {!! Form::text('date_end', old('date_end'), [
                    'class'        => 'form-control pickadate-cobodate',
                    'placeholder'  => 'dd/mm/yyyy',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>

        <div id="debt_days" ng-hide="debtDays">
            <div class="col-xs-4">
                Días de Mora:
                {!! SelectField::input('expired', $data['expired_days']->toArray(), [
                    'class'       => 'form-control input-sm',
                    'placeholder' => 'Dias Mora',
                    'id'          => 'expired'
                  ], old('expired'))
                !!}
            </div>
        </div>
    @endif
</div>