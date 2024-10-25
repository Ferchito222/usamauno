<div class="page-header">
  @if ($fa->company_state === 'C')
    <h1>
      Póliza {{ $fa->detail->header->prefix }}-{{ $fa->detail->header->issue_number }}
    </h1>
    <h3>
      {{ $fa->detail->client->full_name }}
      
      <span class="label label-primary">{{ $fa->observations->last()->state->state }}</span>
    </h3>

    <p>
      {{ $observation->observation_response }}
    </p>
  @endif
</div>
<div class="text-right">
  <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cerrar</button>
</div>