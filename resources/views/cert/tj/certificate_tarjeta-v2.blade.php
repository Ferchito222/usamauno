
@if($header->issued)
    @for($c = 1; $c <= 2; $c++)
        @if($c==2)
            <page><div style="page-break-before: always;">&nbsp;</div></page>
        @endif
        @foreach($header->details as $detail)

            @if($detail->card=='DC')
                @include('cert.tj.debito-v2')
            @elseif($detail->card=='CC')
                @include('cert.tj.credito-v2')
            @endif

        @endforeach
    @endfor
@else
 Certificado no emitido
@endif