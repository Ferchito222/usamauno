<div style="background-color: #eef9f8;">
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
    
    @elseif($code == 'vi' || $code == 'vp' || $code == 'vm' || $code == 'vs')
        <div class="col-xs-4">
            Nro. de Póliza:
            {!! Form::text('policy_number', old('policy_number'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Nro. de Póliza',
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
    
    @endif
    @if(request()->route()->getParameter('type') !== 'BW')
        
        <div class="col-xs-4">
            Fecha (Desde - Hasta)
                {!! Form::text('date_begin', old('date_begin'), [
                    'class'        => 'form-control pickadate-cobodate input-sm',
                    'placeholder'  => 'dd/mm/yyyy',
                    'autocomplete' => 'off'
                ]) !!}

        </div>

        <div class="col-xs-4">
            :
                {!! Form::text('date_end', old('date_end'), [
                    'class'        => 'form-control pickadate-cobodate input-sm',
                    'placeholder'  => 'dd/mm/yyyy',
                    'autocomplete' => 'off'
                ]) !!}

        </div>
        
    @endif
</div>

@if(! request()->route()->hasParameter('guest'))
    <div class="form-group">
        <div class="col-xs-4">
            Sucursal:
            {!! SelectField::input('city', $data['cities'], [
                'class'       => 'form-control input-sm',
                'placeholder' => 'Sucursal',
              ], old('city'))
            !!}
        </div>
        <div class="col-xs-4">
            Agencia:
            {!! SelectField::input('agency', $data['agencies'], [
                'class'       => 'form-control input-sm',
                'placeholder' => 'Agencia',
              ], old('agency'))
            !!}
        </div>
        <div class="col-xs-4">
            Usuario:
            {!! SelectField::input('username', $data['users'], [
                'class'       => 'form-control input-sm',
                'placeholder' => 'Agencia',
              ], old('username'))
            !!}
        </div>
    </div>
    
@endif

<div class="form-group">
    <div class="col-xs-4">
        Cliente:
            {!! Form::text('client', old('client'), [
              'class'        => 'form-control ui-wizard-content input-sm',
              'placeholder'  => 'Cliente',
              'autocomplete' => 'off'])
            !!}
    </div>

    
    <div class="col-xs-4">
        C.I.:
            {!! Form::text('dni', old('dni'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'C.I.',
                'autocomplete' => 'off'
            ]) !!}
        
    </div>

    <div class="col-xs-4">
        Extensión:
        {!! Form::text('extension', old('extension'), [
            'class'        => 'form-control ui-wizard-content input-sm',
            'placeholder'  => 'Extensión',
            'autocomplete' => 'off'
        ]) !!}
    </div>
</div>


@if ((isset($type) && $type === 'Q') || request()->route()->getParameter('type') === 'QTE')
    
@else
    @if($code === 'de' && request()->route()->getParameter('type') === 'ISS')
        <div class="form-group">    
            <div class="col-xs-4">
                Nro. de Operación:
                {!! Form::text('operation_number', old('operation_number'), [
                    'class'        => 'form-control ui-wizard-content input-sm',
                    'placeholder'  => 'Nro. de Operación',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>
    @endif
@endif
<br />
</div>    
<hr>
@if(request()->route()->hasParameter('code'))
    
    @include('report.partials.inputs-search-' . $code)
@endif

{{ csrf_field() }}

<div class="text-right">
    {!! Form::button('Restablecer campos <i class="icon-reset position-right"></i>', ['type' => 'reset', 'class' => 'btn btn-default']) !!}
    {!! Form::button('Buscar <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
</div>