<div class="panel-body ">
    <div class="col-xs-12 col-md-12">
        <div class="form-group">
            <label class="control-label col-lg-1">Póliza: </label>
            <div class="col-lg-3">
                <div class="input-group">
                    <span class="input-group-addon">Nro</span>
                    <input name="numero_poliza" value="{{ $valueForm['numero_poliza'] }}" type="text" class="form-control ui-wizard-content" placeholder="Nro. Póliza">
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
    <div class="col-xs-6 col-md-4">
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
    <div class="col-xs-12">
        <div class="form-group col-md-8">
            <label class="control-label col-lg-1">Fecha: </label>
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
        @if($flag == 2)
        <div class="form-group col-md-4">
            <label class="radio-inline radio-right">Anulados: </label>
            <label class="radio-inline radio-left">
                <input type="radio" name="anulados" value="1" class="styled" @if($valueForm['anulados']==1) checked @endif>
                       Si
            </label>
            <label class="radio-inline radio-left">
                <input type="radio" name="anulados" value="2" class="styled" @if($valueForm['anulados']==2) checked @endif>
                       No
            </label>
            <label class="radio-inline radio-left">
                <input type="radio" name="anulados" value="3" class="styled" @if($valueForm['anulados']==3) checked @endif>
                       Todos
            </label>
        </div>
        @endif
    </div>
    <input type="hidden" id="xls_download" name="xls_download" value="0">
    <input type="hidden" id="flag" name="flag" value="{{ $flag }}">
</div>
@if($flag == 1)
<div class="col-md-12">
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="checkbox-inline checkbox-right">
                        <strong>Estado:</strong> &nbsp;
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" class="styled" name="pendiente" value="1" id="pendiente" @if($valueForm['pendiente']==1) checked @endif>     
                               Pendiente
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" class="styled" name="subsanado" value="1" id="subsanado" @if($valueForm['subsanado']==1) checked @endif>
                               Subsanado/Pendiente
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" class="styled" name="observado" value="1" id="observado" @if($valueForm['observado']==1) checked @endif>
                               Observado
                    </label>
                </div>
                <!-- Solo generales (estado facultativo)-->
                @if($flag == 1)
                <div class="form-group">
                    @foreach($rp_state as $state)
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" class="styled" name="{{ $state->states->slug }}" value="1" id="{{ $state->states->slug }}" @if($valueForm[$state->states->slug]==1) checked @endif>     
                               {{ $state->states->state }}
                    </label>
                    @endforeach
                </div>
                @endif
                <hr />
                <div class="form-group">
                    <label class="checkbox-inline checkbox-right">
                        <strong>Aprobado:</strong> &nbsp;
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" value="1" class="styled" name="freecover" id="freecover" @if($valueForm['freecover']==1) checked @endif>
                               Free Cover
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" value="1" class="styled" name="no_freecover" id="no_freecover" @if($valueForm['no_freecover']==1) checked @endif>
                               No Free Cover
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" value=1"" class="styled" name="extraprima" id="extraprima" @if($valueForm['extraprima']==1) checked @endif>
                               Extraprima
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" value="1" class="styled" name="no_extraprima" id="no_extraprima" @if($valueForm['no_extraprima']==1) checked @endif>
                               No Extraprima
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" value="1" class="styled" name="emitido" id="emitido" @if($valueForm['emitido']==1) checked @endif>
                               Emitido
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" value="1" class="styled" name="no_emitido" id="no_emitido" @if($valueForm['no_emitido']==1) checked @endif>
                               No Emitido
                    </label>
                </div>
                <hr />
                <div class="form-group col-md-4">
                    <label class="checkbox-inline checkbox-right">
                        <strong>Rechazado/Anulado:</strong> &nbsp;
                    </label>
                    <label class="checkbox-inline checkbox-left">
                        <input type="checkbox" class="styled" value="1" name="rechazado" id="rechazado" @if($valueForm['rechazado']==1) checked @endif>
                               Rechazado
                    </label>
                </div>
                <div class="form-group col-md-4">
                    <label class="checkbox-inline checkbox-right">
                        <strong>Anulado:</strong> &nbsp;
                    </label>
                    <label class="radio-inline radio-left">
                        <input type="radio" name="anulados" value="1" class="styled" @if($valueForm['anulados']==1) checked @endif>
                               Si
                    </label>
                    <label class="radio-inline radio-left">
                        <input type="radio" name="anulados" value="2" class="styled" @if($valueForm['anulados']==2) checked @endif>
                               No
                    </label>
                    <label class="radio-inline radio-left">
                        <input type="radio" name="anulados" value="3" class="styled" @if($valueForm['anulados']==3) checked @endif>
                               Todos
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@endif