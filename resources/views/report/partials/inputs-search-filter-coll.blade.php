
@if(request()->route()->getParameter('type') == 'COLL')
    <div class="form-group">
        <div class="col-xs-4">
            Nro de Emisión:
            {!! Form::text('issue_number', old('issue_number'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Nro. EMISION',
                'autocomplete' => 'off'
            ]) !!}
        </div>
        <div class="col-xs-4">
            Fecha de transacción<br />
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                {!! Form::text('date_begin', old('date_begin'), [
                    'class'        => 'form-control pickadate-cobodate input-sm',
                    'placeholder'  => 'Fecha desde',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>

        <div class="col-xs-4">
            : <br />
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                {!! Form::text('date_end', old('date_end'), [
                    'class'        => 'form-control pickadate-cobodate input-sm',
                    'placeholder'  => 'Fecha hasta',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>
        <div class="col-xs-4">
            Cobrados:
            {!! SelectField::input('charged', [['id'=>'','name'=>'Todos'],['id'=>1,'name'=>'Cobrados'], ['id'=>0,'name'=>'Pendientes']], [
                'class'       => 'form-control ui-wizard-content input-sm',
                'id' => 'month',
                'placeholder' => 'Cobrado',
              ], old('charged'))
            !!}
        </div>
        <div class="col-xs-4">
            Fecha de Couta<br />
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                {!! Form::text('date_begin2', old('date_begin2'), [
                    'class'        => 'form-control pickadate-cobodate input-sm',
                    'placeholder'  => 'Fecha desde',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>

        <div class="col-xs-4">
            : <br />
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                {!! Form::text('date_end2', old('date_end2'), [
                    'class'        => 'form-control pickadate-cobodate input-sm',
                    'placeholder'  => 'Fecha hasta',
                    'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>
        <div class="col-xs-4">
            Código de Cliente:
            {!! Form::text('code', old('code'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Código de Cliente',
                'autocomplete' => 'off'
            ]) !!}
        </div>
        <div class="col-xs-4">
            Tipo de Tarjeta:
            {!! SelectField::input('card', $data['account_number_types']->toArray(), [
                'class'       => 'form-control ui-wizard-content input-sm',
                'placeholder' => 'Tipo de Tarjeta',
              ], old('card'))
            !!}
        </div>
        <div class="col-xs-4">
            Nro de Tarjeta:
            {!! Form::text('card_number', old('card_number'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Nro de Tarjeta',
                'autocomplete' => 'off'
            ]) !!}
        </div>
        <div class="col-xs-4">
            Nro de Cuenta:
            {!! Form::text('account_number', old('account_number'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Nro de Cuenta',
                'autocomplete' => 'off'
            ]) !!}
        </div>
        <div class="col-xs-4">
            Caja de ahorro:
            {!! Form::text('savings_bank', old('savings_bank'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Caja de ahorro',
                'autocomplete' => 'off'
            ]) !!}
        </div>
        <div class="col-xs-4">
            Nro de Transacción:
            {!! Form::text('transaction_number', old('transaction_number'), [
                'class'        => 'form-control ui-wizard-content input-sm',
                'placeholder'  => 'Nro de Transacción',
                'autocomplete' => 'off'
            ]) !!}
        </div>
        <div class="col-xs-4">
            Estado del Certificado:
            {!! SelectField::input('canceled', [['id'=>'','name'=>'Todos'],['id'=>1,'name'=>'Anulados'], ['id'=>0,'name'=>'Vigentes']], [
                'class'       => 'form-control ui-wizard-content input-sm',
              ], old('canceled'))
            !!}
        </div>
    </div>
@endif


@if(request()->route()->hasParameter('code'))
  
    @include('report.partials.inputs-search-' . $code)
@endif

{{ csrf_field() }}

<div class="text-right">
    @if($code=='tj')
        <a href="{{ route('report.master.collection', [ 'code' => $code, 'rp_id' => $rp_id, 'type' => 'COLL' ]) }}" class="btn btn-default">
            Restablecer campos <i class="icon-reset position-right"></i>
        </a>
    @else
        {!! Form::button('Restablecer campos <i class="icon-reset position-right"></i>', ['type' => 'reset', 'class' => 'btn btn-default']) !!}
    @endif
    {!! Form::button('Buscar <i class="icon-search4 position-right"></i>', ['type' => 'submit', 'class' => 'btn btn-primary']) !!}
</div>