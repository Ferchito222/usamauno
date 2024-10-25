@var $req = ''

@if(isset($required))
    @if($required === 1)
        @var $req = 'label_required'
    @endif    
@endif    

@if($type === 'text' || $type === 'text2')
    <div class="form-group">
        @if(isset($label))
            <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        @endif
        <div class="col-lg-{{(isset($label))?'9':'12'}}">
            @if(isset($icon))
            <div class="input-group">
                <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
            @endif
            @if($type === 'text2')
                {!! Form::text($id, old($id, $value) , [
                    'class'        => 'form-control ui-wizard-content',
                    'id'           => $id,
                    'placeholder'  => (isset($label))?$label:'',
                    'readonly'  => '1',
                    'autocomplete' => 'off'
                    ]) !!}
            @else
                {!! Form::text($id, old($id, $value) , [
                'class'        => 'form-control ui-wizard-content',
                'id'           => $id,
                'placeholder'  => (isset($label))?$label:'',
                'autocomplete' => 'off'
                ]) !!}
            @endif    
            @if(isset($icon))    
            </div>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif

@if($type === 'textv' || $type === 'text2v')
    <div class="form-group">
        @if(isset($label))
            <label class="{{ $req }}">{{ $label }}: </label>
        @endif
        
            @if(isset($icon))
            <div class="input-group">
                <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
            @endif
            @if($type === 'text2')
                {!! Form::text($id, old($id, $value) , [
                    'class'        => 'form-control ui-wizard-content',
                    'id'           => $id,
                    'placeholder'  => (isset($label))?$label:'',
                    'readonly'  => '1',
                    'autocomplete' => 'off'
                    ]) !!}
            @else
                {!! Form::text($id, old($id, $value) , [
                'class'        => 'form-control ui-wizard-content',
                'id'           => $id,
                'placeholder'  => (isset($label))?$label:'',
                'autocomplete' => 'off'
                ]) !!}
            @endif    
            @if(isset($icon))    
            </div>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        
    </div>
@endif
@if($type === 'text3')
    <div class="form-group">
        <div class="col-lg-12">
                {!! Form::text($id, old($id, $value) , [
                'class'        => 'form-control ui-wizard-content',
                'id'           => $id,
                'placeholder'  => $label,
                'autocomplete' => 'off'
                ]) !!}
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif

@if($type === 'password')
    <div class="form-group">
        <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            @if(isset($icon))
            <div class="input-group">
                <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
            @endif
                {!! Form::password($id, old($id, $value) , [
                'class'        => 'form-control',
                'id'           => $id,
                'placeholder'  => $label,
                'autocomplete' => 'off'
                ]) !!}    
            @if(isset($icon))    
            </div>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif

@if($type === 'hidden')
    <input type="hidden" id="{{ $id }}" name="{{ $id }}" value="{{ old($id,$value) }}">
@endif

@if($type === 'hidden_ng')
    <input type="hidden" id="{{ $id }}" name="{{ $id }}" value="{{ $value }}" ng-model="formData.{{ $id }}">
@endif

@if($type === 'textarea')
    <div class="form-group">
        <label class="control-label col-lg-3 {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            {!! Form::textarea($id, old($id, $value), [
            'size' => '4x4',
            'class' => 'form-control',
            'id' => $id,
            'placeholder' => $label,
            'autocomplete' => 'off'])
            !!}
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'ci' || $type === 'nit')
    <div class="form-group">
        <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            <div class="input-group">
                @if($type === 'ci')
                <span class="input-group-addon">C.I.</span>
                @else
                <span class="input-group-addon">N.I.T.</span>
                @endif
                {!! Form::text($id, old($id, $client->dni), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => $label,
                'autocomplete' => 'off'
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'date')
    <div class="form-group">
        <label class="control-label col-lg-3 {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                @if(isset($value))
                    {!! Form::text($id, old($id, dateToFormat($value)), [
                    'class'        => 'form-control pickadate-cobodate',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Seleccione fecha'
                    ]) !!}
                @else
                    {!! Form::text($id, old($id), [
                    'class'        => 'form-control pickadate-cobodate',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Seleccione fecha'
                    ]) !!}
                @endif
            </div>
            @if(isset($help))
                <span class="help-block">{!!$help!!}</span>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'date_readonly')
    <div class="form-group">
        <label class="control-label col-lg-3 {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                @if(isset($value))
                    {!! Form::text($id, old($id, dateToFormat($value)), [
                    'class'        => 'form-control',
                    'autocomplete' => 'off',
                    'readonly' => '1',
                    'placeholder'  => 'Seleccione fecha'
                    ]) !!}
                @else
                    {!! Form::text($id, old($id), [
                    'class'        => 'form-control',
                    'autocomplete' => 'off',
                    'readonly' => '1',
                    'placeholder'  => 'Seleccione fecha'
                    ]) !!}
                @endif
            </div>
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'email')
    <div class="form-group">
        <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            <div class="input-group">
                <span class="input-group-addon">@</span>
                {!! Form::email($id, old($id, $value), [
                'class'        => 'form-control ui-wizard-content',
                'placeholder'  => 'mail@email.com',
                'autocomplete' => 'off'
                ]) !!}
            </div>
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'combo')
    <div class="form-group">
        <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            @if(isset($icon))
            <div class="input-group">
                <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
            @endif
            @if(isset($value))
                {!! SelectField::input($id, $array, [
                'class' => 'select-search',
                'id' => $id
                ], old($id, $value)) !!}
            @else
                {!! SelectField::input($id, $array, [
                'class' => 'select-search',
                'id' => $id
                ], old($id)) !!}
            @endif
            @if(isset($icon))
            </div>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'combo1')
    <div class="form-group">
        <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            @if(isset($icon))
            <div class="input-group">
                <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
            @endif
            @if(isset($value))
                {!! SelectField::input($id, $array, [
                'class' => 'form-control',
                'id' => $id,
                'onfocus' => 'this.blur()'
                ], old($id, $value)) !!}
            @else
                {!! SelectField::input($id, $array, [
                'class' => 'form-control',
                'id' => $id,
                'onfocus' => 'this.blur()'
                ], old($id)) !!}
            @endif
            @if(isset($icon))
            </div>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'combo2')
    <div class="form-group">
        @if(isset($label))
            <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        @endif
        <div class="col-lg-{{(isset($label))?'9':'12'}}">
            @if(isset($icon))
            <div class="input-group">
                <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
            @endif
            @if(isset($value))
                {!! SelectField::input($id, $array, [
                'class' => 'form-control',
                'id' => $id
                ], old($id, $value)) !!}
            @else
                {!! SelectField::input($id, $array, [
                'class' => 'form-control',
                'id' => $id
                ], old($id)) !!}
            @endif
            @if(isset($icon))
            </div>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif

@if($type === 'combo2v')
    <div class="form-group">
        @if(isset($label))
            <label class="{{ $req }}">{{ $label }}: </label>
        @endif
        
        @if(isset($icon))
        <div class="input-group">
            <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
        @endif
        @if(isset($value))
            {!! SelectField::input($id, $array, [
            'class' => 'form-control',
            'id' => $id
            ], old($id, $value)) !!}
        @else
            {!! SelectField::input($id, $array, [
            'class' => 'form-control',
            'id' => $id
            ], old($id)) !!}
        @endif
        @if(isset($icon))
        </div>
        @endif
        <label id="location-error" class="validation-error-label" for="location">{{ $errors->first($id) }}</label>
    </div>
@endif
@if($type === 'multiple')
    <div class="form-group">
        <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            @if(isset($icon))
            <div class="input-group">
                <span class="input-group-addon"><i class="{{ $icon }}"></i></span>
            @endif
            @if(isset($value))
                {!! SelectField::input($id, $array, [
                'class' => 'form-control',
                'id' => $id,
                'data-popup' => 'tooltip',
                'title' => 'Presione [Ctrl] para seleccionar mas de uno',
                'multiple' => 'multiple'
                ], old($id, $value)) !!}
            @else
                {!! SelectField::input($id, $array, [
                'class' => 'form-control',
                'id' => $id,
                'data-popup' => 'tooltip',
                'title' => 'Presione [Ctrl] para seleccionar mas de uno',
                'multiple' => 'multiple'
                ], old($id)) !!}
            @endif
            @if(isset($icon))
            </div>
            @endif
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'radio')
    <div class="form-group">
        <label class="col-lg-3 control-label {{ $req }}">{{ $label }}: </label>
        <div class="col-lg-9">
            @var $cont = 1
            @foreach($array as $key => $content)
                @if($content['id'] !== '')
                    @var $selected =''
                        @if($value != '' && $content['id'] == $value)
                            @var $selected ='checked'
                        @endif
                    
                    <label class="radio-inline">
                        <input type="radio" value="{{ $content['id'] }}" name="{{ $id }}" {{ $selected }}>
                        {{ $content['name'] }}
                    </label>
                    @var $cont++
                @endif
            @endforeach
            
            <label id="location-error" class="validation-error-label"
                   for="location">{{ $errors->first($id) }}</label>
        </div>
    </div>
@endif
@if($type === 'menu-header')
    @var $arr['au'] = ['title'=>'Seguro de Automotores', 'product'=>'Automotores']
    @var $arr['td'] = ['title'=>'Seguro de Todoriesgo', 'product'=>'Todoriesgo']
    @var $arr['ema'] = ['title'=>'Seguro de Equipo y Maquinaria de Contratistas', 'product'=>'Equipo y Maquinaria de Contratistas']
    @var $arr['rma'] = ['title'=>'Seguro de Rotura de Maquinaria', 'product'=>'Rotura de Maquinaria']
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span
                            class="text-semibold">{{ $arr[$product]['title'] }}</span></h4>

                <ul class="breadcrumb breadcrumb-caret position-right">
                    <li><a href="">Inicio</a></li>
                    <li><a href="">{{ $arr[$product]['product'] }}</a></li>
                    <li class="active">Cotizar</li>
                </ul>
            </div>

        </div>
    </div>
@endif


@if($type === 'label')
    <div class="form-group">
        <label class="col-lg-5 control-label"><strong>{{ $label }}:</strong> </label>
        <label class="col-lg-7 control-label">{{$value}}</label>
    </div>
@endif
