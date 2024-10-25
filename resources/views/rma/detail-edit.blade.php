<div class="panel panel-flat small_body">
    <div class="panel-heading divhr">
        <h6 class="form-wizard-title2 text-semibold">
            <span class="col-md-11">
                <span class="form-wizard-count">2</span>
                Datos del Riesgo
                <small class="display-block">Información del riesgo a asegurar</small>
            </span>
        </h6>
    </div>

    {!! Form::open(['route' => ['rma.detail.update', 'rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => $detail_id],
    'method'        => 'put',
    'class'         => 'form-horizontal',
    'ng-controller' => 'DetailRmaController',
    'ng-submit'     => 'update($event)',
    ]) !!}

    @include('rma.partials.detail-create')
    
    <div class="modal-footer">
        <script ng-if="success.detail">
            $(function () {
                messageAction('succes', 'Riesgo fue actualizado correctamente');
            });
        </script>
        <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">
            Cancelar
        </button>
        <span ng-if="btn_save">
            {!! Form::button('Actualizar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        </span>
    </div>
    {!! Form::close() !!}
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("select.select-search").select2({
            dropdownParent: "#popup"
        });
    });
</script>
