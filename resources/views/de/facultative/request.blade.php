<div class="panel-heading divhr">
    <h6 class="form-wizard-title2 text-semibold">
        <span class="col-md-11">
            <span class="form-wizard-count">4</span>
            Solicitud de aprobación a caso facultativo
        </span>
    </h6>
</div>
<br>

{!! Form::open(['route' => ['de.fa.request.store',  'rp_id' => $rp_id, 'header_id' => encode($header->id)],
  'method'        => 'put',
  'class'         => 'form-horizontal',
  'ng-controller' => 'HeaderDeController',
  'ng-submit'     => 'requestStore($event)' ]) !!}

  {!! Form::textArea('facultative_observation', null, [
      'size'         => '4x4',
      'class'        => 'form-control',
      'placeholder'  => 'Observación del Caso Facultativo',
      'autocomplete' => 'off',
      'ng-model'     => 'formData.facultative_observation'
  ]) !!}

  <label id="location-error" class="validation-error-label" for="location" ng-show="errors.facultative_observation">@{{ errors.facultative_observation[0] }}</label>

  <div class="text-right">
    <br>
    <script ng-if="success.facultative">
        $(function(){messageAction('succes', 'La solicitud fue enviada');});
    </script>

    <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cancelar</button>

    {!! Form::button('Enviar Solicitud <i class="icon-floppy-disk position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
  </div>

{!! Form::close() !!}
