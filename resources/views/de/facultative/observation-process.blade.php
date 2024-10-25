<div class="page-header">
  @if ($fa->company_state === 'A' || $fa->company_state === 'R')
    <h1>
      Póliza {{ $fa->detail->header->prefix }}-{{ $fa->detail->header->issue_number }}
    </h1>
    <h3>
      {{ $fa->detail->client->full_name }}
      
      <span class="label label-primary">{{ config('base.company_state.' . $fa->company_state) }}</span>
    </h3>

    <p>
      {{ $fa->observation }}
    </p>
  @endif
</div>

<div class="text-right">
  <button type="button" class="btn border-slate text-slate-800 btn-flat" data-dismiss="modal">Cerrar</button>
</div>