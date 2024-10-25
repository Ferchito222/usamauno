<div class="form-group">
    @if(request()->route()->getParameter('type') === 'GEN')
        <label class="label label-info" style="font-size: 100%;">Estado</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[issued][1]" {{ old('cc1.issued.1') == 1 ? 'checked' : '' }}>
                Emitido
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[issued][0]" {{ old('cc1.issued.0') == 1 ? 'checked' : '' }}>
                No Emitido
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[canceled][1]" {{ old('cc1.canceled.1') == 1 ? 'checked' : '' }}>
                Anulado
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[is_client][1]" {{ old('cc1.is_client.1') == 1 ? 'checked' : '' }}>
                Cliente
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[is_client][0]" {{ old('cc1.is_client.0') == 1 ? 'checked' : '' }}>
                No Cliente
            </label>
        </div>
        <br>

        <label class="label label-info" style="font-size: 100%;">Estado de Cuenta</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="state[elp][pe]" {{ old('state.elp.pe') == 1 ? 'checked' : '' }}>
                Pendiente
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="state[elp][np]" {{ old('state.elp.np') == 1 ? 'checked' : '' }}>
                Impago
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="state[elp][va]" {{ old('state.elp.va') == 1 ? 'checked' : '' }}>
                Vigente
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="state[elp][ca]" {{ old('state.elp.ca') == 1 ? 'checked' : '' }}>
                Cancelado
            </label>
        </div>
    @elseif(request()->route()->getParameter('type') === 'ISS')
        <label class="label label-info" style="font-size: 100%;">Anulado</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[canceled][1]" {{ old('cc1.canceled.1') == 1 ? 'checked' : '' }}> SI
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[canceled][0]" {{ old('cc1.canceled.0') == 1 ? 'checked' : '' }}> NO
            </label>
        </div>
    @endif
</div>