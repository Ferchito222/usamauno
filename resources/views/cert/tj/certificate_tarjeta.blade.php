
@if($header->issued)
    @for($c = 1; $c <= 2; $c++)
        @if($c==2)
            <page><div style="page-break-before: always;">&nbsp;</div></page>
        @endif
        @foreach($header->details as $detail)

            @if($detail->card=='DC')
                @include('cert.tj.debito')
            @elseif($detail->card=='CC')
                @include('cert.tj.credito')
            @endif

        @endforeach
    @endfor
@else
 Certificado no emitido
@endif