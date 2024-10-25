<div class="panel panel-flat small_body">
    @if(! request()->has('coverage'))
        <div class="panel-heading divhr">
            <h6 class="form-wizard-title2 text-semibold">
            <span class="col-md-11">
                <span class="form-wizard-count">4</span>
                Datos del Riesgo
            </span>
            </h6>
        </div>
    @endif

    {!! Form::open(['route' => ['rma.detail.i.update',
        'rp_id'     => $rp_id,
        'header_id' => $header_id,
        'detail_id' => $detail_id,
        isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
    ],
        'method'        => 'put',
        'class'         => 'form-horizontal',
        'ng-controller' => 'DetailRmaController',
        'ng-submit'     => 'updateIssuance($event)',
    ]) !!}
        @include('rma.partials.detail-edit')

        @if(request()->has('coverage'))
            <input type="hidden" ng-init="formData.coverage='{{ request()->get("coverage") }}'">
        @endif
        <div class="modal-footer">
            <script ng-if="success.detail">
                $(function () {
                    messageAction('succes', 'Riesgo actualizado correctamente.');
                });
            </script>

            <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">
                Cancelar
            </button>

            <button type="submit" class="btn btn-primary">
                @if(request()->has('coverage'))
                    Continuar
                @else
                    Actualizar
                @endif
                <i class="icon-floppy-disk position-right"></i>
            </button>
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