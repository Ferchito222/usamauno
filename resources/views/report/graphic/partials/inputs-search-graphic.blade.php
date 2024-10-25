<div style="background-color: #eef9f8;">
@if(! request()->route()->hasParameter('guest'))
    <div class="form-group">
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
    </div>
    <div class="form-group">
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
    </div>
    
@endif
<br />
</div>    
<hr>

{{ csrf_field() }}

<div class="text-right">
    <a href="{{ route('graphic.report', [ 'formar' => 'html']) }}" class="btn btn-default">
                                        Restablecer campos <i class="icon-reset position-right"></i>
                                    </a>
    {!! Form::button('Buscar <i class="icon-arrow-right14 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
</div>