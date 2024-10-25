<div class="col-xs-12 col-md-12">
    <div class="form-group">
        <label class="control-label col-lg-1">Cotización: </label>
        <div class="col-lg-3">
            <div class="input-group">
                <span class="input-group-addon">Nro.</span>
                <input name="nro_cotizacion" value="{{ $valueForm['nro_cotizacion'] }}" type="text" class="form-control ui-wizard-content" placeholder="Nro. Cotizaci&oacute;n">
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label class="control-label col-lg-4">Sucursal: </label>
        <div class="col-lg-8">
            {!! Form::select('sucursal', 
            (['0' => 'Seleccione'] + $cities), 
            $valueForm['sucursal'], 
            ['class' => 'select-search']) !!}
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label class="control-label col-lg-4">Agencia: </label>
        <div class="col-lg-8">
            {!! Form::select('agencia', 
            (['0' => 'Seleccione'] + $agencies), 
            $valueForm['agencia'], 
            ['class' => 'select-search']) !!}
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-4">
    <div class="form-group">
        <label class="control-label col-lg-2">Usuario: </label>
        <div class="col-lg-10">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                {!! Form::select('usuario', 
                (['0' => 'Seleccione'] + $users), 
                $valueForm['usuario'], 
                ['class' => 'select-search']) !!}
            </div>
        </div>
    </div>
</div>


<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label class="control-label col-lg-4">Cliente: </label>
        <div class="col-lg-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input name="cliente" value="{{ $valueForm['cliente'] }}" type="text" class="form-control ui-wizard-content" placeholder="Cliente">
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-4">
    <div class="form-group">
        <label class="control-label col-lg-2">CI: </label>
        <div class="col-lg-5">
            <input name="ci" value="{{ $valueForm['ci'] }}" type="text" class="form-control ui-wizard-content" placeholder="CI">
        </div>
        <div class="col-lg-5">
            <input name="complement" value="{{ $valueForm['complement'] }}" type="text" class="form-control ui-wizard-content" placeholder="Complemento">
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label class="control-label col-lg-4">Extensión: </label>
        <div class="col-lg-8">
            {!! Form::select('extension', 
            (['0' => 'Seleccione'] + $extencion), 
            $valueForm['extension'], 
            ['class' => 'select-search']) !!}
        </div>
    </div>
</div>
<div class="col-xs-12 col-md-8">
    <div class="form-group">
        <label class="control-label col-lg-2">Fecha Cotización: </label>
        <label class="control-label col-lg-1">Desde: </label>
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                <input name="fecha_ini" type="text" value="{{ $valueForm['fecha_ini'] }}" class="form-control pickadate-cobodate" placeholder="Desde">
            </div>
        </div>
        <label class="control-label col-lg-1">Hasta: </label>
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                <input name="fecha_fin" type="text" value="{{ $valueForm['fecha_fin'] }}" class="form-control pickadate-cobodate" placeholder="Hasta">
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="xls_download" name="xls_download" value="0">