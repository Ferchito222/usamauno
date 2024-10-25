<div class="panel panel-flat small_body">
    <div class="panel-heading divhr">
        <h6 class="form-wizard-title2 text-semibold">
            <span class="col-md-11">
                <span class="form-wizard-count">2</span>
                Datos del Riesgo
            </span>
        </h6>
    </div>
    {!! Form::open(['route' => ['td.mr.edit', 'rp_id' => $rp_id, 'header_id' => $header_id, 'detail_id' => $detail_id],
        'method'        => 'put',
        'class'         => 'form-horizontal',
        'ng-controller' => 'DetailTdController',
        'ng-submit'     => 'update($event)',
    ]) !!}
        @include('td.partials.fields-risk',['form'=>'create'])

        <div class="modal-footer">
            <script ng-if="success.result">
                $(function () {
                    messageAction('succes', 'El riesgo fue actualizado correctamente');
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