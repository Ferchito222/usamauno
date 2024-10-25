<div class="panel panel-flat small_body">
    <div class="panel-heading divhr">
        <h6 class="form-wizard-title2 text-semibold">
        <span class="col-md-11">
            <span class="form-wizard-count">4</span>
            Datos del Riesgo
        </span>
        </h6>
    </div>
    {!! Form::open(['route' => ['td.mr.i.update',
        'rp_id'     => $rp_id,
        'header_id' => $header_id,
        'detail_id' => $detail_id,
        isset($_GET['idf']) ? 'idf=' . e($_GET['idf']) : null
    ],
        'method'        => 'put',
        'class'         => 'form-horizontal',
        'ng-controller' => 'DetailTdController',
        'ng-submit'     => 'updateIssuance($event)',
    ]) !!}
        <table class="table datatable-basic small_body">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Descripción</th>
                    <th>Número de Serie</th>
                    <th>Estado</th>
                    <th>Uso</th>
                    <th>Valor de Reposición</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ config('base.property_types.' . $detail->matter_insured) }}</td>
                    <td>{{ $detail->matter_description }}</td>
                    <td>{{ $detail->number }}</td>
                    <td>{{ config('base.property_states.' . $detail->state) }}</td>
                    <td>{{ config('base.property_uses.' . $detail->use) }}</td>
                    <td>
                        <strong>{{ number_format($detail->insured_value, 2) }} {{ $header->currency }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
        <br />
        <br />
        @include('td.partials.fields-risk',['form'=>'edit'])
        <div class="modal-footer">
            <script ng-if="success.result">
                $(function () {
                    messageAction('succes', 'El riesgo fue actualizado correctamente.');
                });
            </script>
            <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary">Actualizar <i class="icon-floppy-disk position-right"></i></button>
        </div>
    {!! Form::close() !!}
</div>