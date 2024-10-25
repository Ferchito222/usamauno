<div class="row" ng-controller="FacultativeController">
    <div id="fa-form" class="col-md-12 animated"
         ng-hide="mcEnabled"
         ng-class="{ fadeIn: !mcEnabled, fadeOut: mcEnabled }">
        <!-- Horizontal form -->
        <div class="panel panel-flat border-top-primary">
            <div class="panel-heading divhr">
                <h6 class="form-wizard-title2 text-semibold">
                    <span class="col-md-11">
                        Formulario para aprobar la solicitud no emitida
                        <small class="display-block">Titular {{ $fa->detail->header->client->full_name }}</small>
                    </span>
                </h6>
            </div>
            <br>

            {!! Form::open(['route' => ['au.fa.update', 'rp_id' => $rp_id, 'id' => encode($fa->id)],
                'id'        => 'form-fa',
                'method'    => 'put',
                'class'     => 'form-horizontal',
                'ng-submit' => 'store($event)',
            ]) !!}

            @include('partials.process', ['product' => 'au'])

            {!! Form::close() !!}
        </div>

        <!-- /horizotal form -->
    </div>

    <div id="mc-form" class="col-md-12 animated"
         ng-show="mcEnabled"
         ng-class="{ fadeIn: mcEnabled, fadeOut: !mcEnabled }">
        Por favor espere ...
    </div>
</div>
