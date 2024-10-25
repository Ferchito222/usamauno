<div class="form-group">
    @if ((isset($type) && $type === 'Q') || request()->route()->getParameter('type') === 'QTE')
        <div class="col-xs-4">
            Nro. de Cotización:
            {!! Form::text('quote_number', old('quote_number'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Nro. de Cotización',
                'autocomplete' => 'off'
            ]) !!}
        </div>
    @else
        <div class="col-xs-4">
            Nro. de Certificado:
            {!! Form::text('policy_number', old('policy_number'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Nro. de Certificado',
                'autocomplete' => 'off'
            ]) !!}
        </div>

        @if($code === 'de' && request()->route()->getParameter('type') === 'ISS')
            <div class="col-xs-4">
                Nro. de Operación:
                {!! Form::text('operation_number', old('operation_number'), [
                    'class'        => 'form-control ui-wizard-content input-sm',
                    'placeholder'  => 'Nro. de Operación',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        @endif
    @endif


    @if(! request()->route()->hasParameter('guest'))
        <div class="col-xs-4">
            Sucursal:
            {!! SelectField::input('city', $data['cities'], [
                'class'       => 'select-search',
                'placeholder' => 'Sucursal',
              ], old('city'))
            !!}
        </div>
        <div class="col-xs-4">
            Agencia:
            {!! SelectField::input('agency', $data['agencies'], [
                'class'       => 'select-search',
                'placeholder' => 'Agencia',
              ], old('agency'))
            !!}
        </div>
        <div class="col-xs-4">
            Usuario:
            {!! SelectField::input('username', $data['users'], [
                'class'       => 'select-search',
                'placeholder' => 'Agencia',
              ], old('username'))
            !!}
        </div>
    @endif
    
    <div class="col-xs-4">
        Cliente:
        <div class="input-group">
            <span class="input-group-addon"><i class="icon-user"></i></span>
            {!! Form::text('client', old('client'), [
              'class'        => 'form-control ui-wizard-content',
              'placeholder'  => 'Cliente',
              'autocomplete' => 'off'])
            !!}
        </div>
    </div>
    <div class="col-xs-2">
        C.I.:
        <div class="input-group">
            {!! Form::text('dni', old('dni'), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => 'C.I.',
                'autocomplete' => 'off'
            ]) !!}
        </div>
    </div>
    <div class="col-xs-2">
        Extensión:
        <div class="input-group">
            {!! Form::text('extension', old('extension'), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => 'Extensión',
                'autocomplete' => 'off'
            ]) !!}
        </div>
    </div>

    @if(request()->route()->getParameter('type') !== 'BW')
        <div class="col-xs-4">
            Fecha:
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                {!! Form::text('date_begin', old('date_begin'), [
                    'class'        => 'form-control pickadate-cobodate',
                    'placeholder'  => 'Fecha desde',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>
        <div class="col-xs-4">
            :
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                {!! Form::text('date_end', old('date_end'), [
                    'class'        => 'form-control pickadate-cobodate',
                    'placeholder'  => 'Fecha hasta',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>
        @if(request()->route()->hasParameter('code') && $code=='tj')
            <div class="col-xs-4">
                Tipo de Tarjeta: 
                {!! SelectField::input('card', $data['account_number_types']->toArray(), [
                    'class'       => 'form-control ui-wizard-content input-sm',
                    'placeholder' => 'Tipo de Tarjeta',
                ], old('card'))
                !!}
            </div>
        @endif
        @if(request()->route()->hasParameter('code') && $code=='de')
            <div class="col-xs-4">
                Tipo de Solicitud: 
                {!! SelectField::input('type_of_request', $data['types_of_requests']->toArray(), [
                    'class'       => 'form-control ui-wizard-content input-sm',
                    'placeholder' => 'Tipo de Solicitud',
                ], old('type_of_request'))
                !!}
            </div>
            <div class="col-xs-4">
                Seguro de Cesantía: 
                {!! SelectField::input('cesantia', $data['yes_no']->toArray(), [
                    'class'       => 'form-control ui-wizard-content input-sm',
                ], old('cesantia'))

                !!}
            </div>
        @endif
    @endif
</div>
@if(request()->route()->hasParameter('code'))
    <hr>
    @include('report.partials.inputs-search-' . $code)
@endif

{{ csrf_field() }}

<div class="text-right">
    {!! Form::button('Restablecer campos <i class="icon-reset position-right"></i>', ['type' => 'reset', 'class' => 'btn btn-default']) !!}
    {!! Form::button('Buscar <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
</div>
