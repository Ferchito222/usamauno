@if(isset($header))
    <h3>WS Error: Certificado No. {{ $header->prefix }}-{{ $header->issue_number }}</h3>
@elseif(isset($credit_line))
    <h3>WS Error: Línea de Crédito No. {{ $credit_line->number_line }}</h3>
@endif

<div>
    <div style="text-align: justify; padding: 10px 50px 10px 10px; color: #3A3A3A; font-weight: bold;">
        Método: <strong>RegistrarDatos</strong>
        <hr>
        <ul style="list-style-type: circle;">
            @foreach($records as $record)
                @foreach($record as $key => $value)
                    <li>
                        <strong>{{ $key }}</strong> =>
                        <strong>{{ (is_bool($value) ? ($value ? 'true' : 'false') : $value) }}</strong>
                    </li>
                @endforeach
                <li><hr></li>
            @endforeach
        </ul>
    </div>
</div>