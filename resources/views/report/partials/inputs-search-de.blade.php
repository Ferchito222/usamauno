<div class="form-group">
    @if(request()->route()->getParameter('type') === 'GEN')
        <label class="label label-info" style="font-size: 100%;">Estado</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc2[state1][pe]" {{ old('cc2.state1.pe') == 1 ? 'checked' : '' }}>
                Pendiente
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc2[state1][sp]" {{ old('cc2.state1.sp') == 1 ? 'checked' : '' }}>
                Subsanado / Pendiente
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc2[state1][ob]" {{ old('cc2.state1.ob') == 1 ? 'checked' : '' }}>
                Observado
            </label>
            <br>

            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc2[state2][cl]" {{ old('cc2.state2.cl') == 1 ? 'checked' : '' }}>
                Aclaraciones
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc2[state2][me]" {{ old('cc2.state2.me') == 1 ? 'checked' : '' }}>
                Exámenes Médicos y/o Requisitos
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc2[state2][de]" {{ old('cc2.state2.de') == 1 ? 'checked' : '' }}>
                Error en Datos
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc2[state2][re]" {{ old('cc2.state2.re') == 1 ? 'checked' : '' }}>
                Reaseguro
            </label>
        </div>
        <br>

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
                <input type="checkbox" value="1" name="cc1[free_cover][2]" {{ old('cc1.free_cover.2') == 1 ? 'checked' : '' }}>
                Aprobado por Muerte Accidental
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[extra][1]" {{ old('cc1.extra.1') == 1 ? 'checked' : '' }}>
                Extraprima
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[extra][0]" {{ old('cc1.extra.0') == 1 ? 'checked' : '' }}>
                No Extraprima
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

        <label class="label label-info" style="font-size: 100%;">Rechazado / Anulado / Hipotecario / Cesantía</label>
        <div>
            <label class="checkbox-inline">
                <input type="checkbox" value="1" name="cc1[rejected]" {{ old('cc1.rejected') == 1 ? 'checked' : '' }}>
                Rechazado
            </label>
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
            <label class="checkbox-inline checkbox-right" style="margin-left: 100px;">
                <strong>Hipotecario:</strong>
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[pmo_gen][1]" {{ old('cc1.pmo_gen.1') == 1 ? 'checked' : '' }}> SI
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[pmo_gen][0]" {{ old('cc1.pmo_gen.0') == 1 ? 'checked' : '' }}> NO
            </label>
            <label class="checkbox-inline checkbox-right" style="margin-left: 100px;">
                <strong>Cesantía:</strong>
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[7x5][1]" {{ old('cc1.7x5.1') == 1 ? 'checked' : '' }}> SI
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[7x5][0]" {{ old('cc1.7x5.0') == 1 ? 'checked' : '' }}> NO
            </label>
        </div>
        <br>

        <label class="label label-info" style="font-size: 100%;">Cobertura</label>
        <div>
            @foreach(config('base.client_coverages') as $key => $coverage)
                <label class="checkbox-inline">
                    <input type="checkbox" value="1"
                           name="cc3[coverage][{{ $key }}]" {{ old('cc3.coverage.' . $key) == 1 ? 'checked' : '' }}>
                    {{ $coverage }}
                </label>
            @endforeach
        </div>
        <br>

        <label class="label label-info" style="font-size: 100%;">Producto Crediticio</label>
        <div>
            @foreach($data['retailerProduct']->creditProducts as $creditProduct)
                @if(! in_array($creditProduct->slug, [ 'PMO', 'PLT', '7x5' ]))
                    <label class="checkbox-inline">
                        <input type="checkbox" value="1"
                               name="cc3[creditProduct][{{ $creditProduct->slug }}]" {{ old('cc3.creditProduct.' . $creditProduct->slug) == 1 ? 'checked' : '' }}>
                        {{ $creditProduct->name }}
                    </label>
                @endif
            @endforeach
        </div>
        <br>

    @elseif(request()->route()->getParameter('type') === 'ISS')
        <label class="label label-info" style="font-size: 100%;">Anulado / Certificado Adjunto</label>
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

            <label class="checkbox-inline checkbox-right" style="margin-left: 100px;">
                <strong>Certificado Adjunto:</strong>
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[attached][1]" {{ old('cc1.attached.1') == 1 ? 'checked' : '' }}> SI
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" value="1"
                       name="cc1[attached][0]" {{ old('cc1.attached.0') == 1 ? 'checked' : '' }}> NO
            </label>
        </div>
    @endif
</div>