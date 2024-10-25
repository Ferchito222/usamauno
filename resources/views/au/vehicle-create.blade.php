<div class="panel panel-flat small_body">
    <div class="panel-heading divhr">
        <h6 class="form-wizard-title2 text-semibold">
            <span class="col-md-11">
                <span class="form-wizard-count">2</span>
                Datos del Vehículo
                <small class="display-block">Información del vehículo a asegurar</small>
            </span>
        </h6>
    </div>

    {!! Form::open(['route' => ['au.vh.store', 'rp_id' => $rp_id, 'header_id' => $header_id],
    'method'        => 'post',
    'class'         => 'form-horizontal',
    'ng-controller' => 'DetailAuController',
    'ng-submit'     => 'store($event)',
    ]) !!}

    @include('au.partials.vehicle-create')

    <div class="modal-footer">
        <script ng-if="success.vehicle">
            $(function () {
                messageAction('succes', 'El vehículo fue registrado correctamente');
            });
        </script>
        <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">
            Cancelar
        </button>
        <span ng-if="btn_save">
            {!! Form::button('Registrar <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
        </span>        
    </div>

    {!! Form::close() !!}
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select-search").select2({
            dropdownParent: "#popup"
        });
    });
</script>