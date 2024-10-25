<ul class="nav nav-tabs nav-tabs-highlight">
    <li class="{{ request()->route()->getParameter('type') === 'GEN' ? 'active' : '' }}">
        <a href="{{ route('report.master', [ 'code' => $code, 'rp_id' => $rp_id, 'type' => 'GEN' ]) }}">
            General
        </a>
    </li>
    <li class="{{ request()->route()->getParameter('type') === 'ISS' ? 'active' : '' }}">
        <a href="{{ route('report.master', [ 'code' => $code,  'rp_id' => $rp_id, 'type' => 'ISS' ]) }}">
            Pólizas Emitidas
        </a>
    </li>
    <li class="{{ request()->route()->getParameter('type') === 'QTE' ? 'active' : '' }}">
        <a href="{{ route('report.master', [ 'code' => $code, 'rp_id' => $rp_id, 'type' => 'QTE' ]) }}">
            Solicitues
        </a>
    </li>
    @if($code == 'de')
        <li class="{{ request()->route()->getParameter('type') === 'BW' ? 'active' : '' }}">
            <a href="{{ route('report.master', [ 'code' => $code, 'rp_id' => $rp_id, 'type' => 'BW' ]) }}">
                Quincenal
            </a>
        </li>
        <li class="{{ request()->route()->getParameter('type') === 'OP' ? 'active' : '' }}">
            <a href="{{ route('report.master', [ 'code' => $code, 'rp_id' => $rp_id, 'type' => 'OP' ]) }}">
                Operaciones bajo Línea
            </a>
        </li>
    @endif
    @if($code == 'tj')
        <li class="{{ request()->route()->getParameter('type') === 'COLL' ? 'active' : '' }}">
            <a href="{{ route('report.master.collection', [ 'code' => $code, 'rp_id' => $rp_id, 'type' => 'COLL' ]) }}">
                Cobranzas
            </a>
        </li>
    @endif
</ul>