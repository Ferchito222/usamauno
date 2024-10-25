<div class="form-group">
    @if(request()->route()->getParameter('type') === 'GEN')
        <label class="label label-info" style="font-size: 100%;">Aprobado</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[free_cover][1]" {{ old('cc1.free_cover.1') == 1 ? 'checked' : '' }}>
                Free Cover
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[free_cover][0]" {{ old('cc1.free_cover.0') == 1 ? 'checked' : '' }}>
                No Free Cover
            </label>
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
    @endif
</div>