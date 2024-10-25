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