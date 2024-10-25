@if($form == 'create')
    <input type="hidden" value="{{ old('code', $client->code) }}" name="code">
    <input type="hidden" value="{{ old('operations', empty($client->operations) ? json_encode([]) : $client->operations) }}" name="operations">
@endif
<input type="hidden" value="{{ old('type', $client->type) }}" name="type">

@if($client->type == 'N' || old('type', $client->type) == 'N' )
    <div class="col-xs-12 col-md-6">
        @include('partials.fields', ['type'=>'text', 'label'=>'Nombres', 'id'=>'first_name','required'=>1,'value'=>$client->first_name])

        @if($form == 'edit' && $errors->has('any_last_name'))
            <div class="col-lg-3"></div>
            <div class="col-lg-9"><label class="validation-error-label">Uno de los apellidos es requerido.</label></div>
        @endif

        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido Paterno',    'id'=>'last_name',          'required'=>1,'value'=>$client->last_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido Materno',    'id'=>'mother_last_name',   'required'=>0,'value'=>$client->mother_last_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido de Casada',  'id'=>'married_name',       'required'=>0,'value'=>$client->married_name])

        @if($form == 'edit')
            @include('partials.fields', ['type'=>'combo', 'label'=>'Estado Civil', 'id'=>'civil_status', 'required'=>1,'value'=>$client->civil_status,'array'=>$data['civil_status']->toArray()])
            @include('partials.fields', ['type'=>'text', 'label'=>'País', 'id'=>'country','required'=>0,'value'=>$client->country])
        @endif

        @if($form == 'create')
            @include('partials.fields', ['type'=>'text',    'label'=>'C.I.',        'id'=>'dni',            'required'=>1,'value'=>$client->dni])
            @include('partials.fields', ['type'=>'text',    'label'=>'Complemento', 'id'=>'complement',     'required'=>0,'value'=>$client->complement])
            @include('partials.fields', ['type'=>'combo',   'label'=>'Extensión',   'id'=>'extension',      'required'=>1,'value'=>$client->extension,'array'=>$data['cities']['CI']->toArray()])
        @endif

        @include('partials.fields', ['type'=>'combo2','label'=>'Género','id'=>'gender','required'=>1,'value'=>$client->gender,'array'=>$data['gender']->toArray()])
    </div>
    <div class="col-xs-12 col-md-6">
        @include('partials.fields', ['type'=>'date','label'=>'Fecha de nacimiento','id'=>'birthdate','required'=>1,'value'=>$client->birthdate])    
        @include('partials.fields', ['type'=>'textarea','label'=>'Dirección','id'=>'home_address','required'=>1,'value'=>$client->home_address])
        @include('partials.fields', ['type'=>'textarea','label'=>'Dirección laboral','id'=>'business_address',   'required'=>0,'value'=>$client->business_address])
        @if($form == 'edit')
            @include('partials.fields', ['type'=>'text',    'label'=>'Lugar de nacimiento', 'id'=>'birth_place',        'required'=>0,'value'=>$client->birth_place])
            @include('partials.fields', ['type'=>'text',    'label'=>'Número de domicilio', 'id'=>'home_number',        'required'=>1,'value'=>$client->home_number])
            @include('partials.fields', ['type'=>'text',    'label'=>'Lugar de Trabajo',    'id'=>'workplace',          'required'=>0,'value'=>$client->workplace])
        @endif

        @include('partials.fields', ['type'=>'combo',   'label'=>'Ocupación',               'id'=>'ad_activity_id',         'required'=>1,'value'=>$client->ad_activity_id,'array'=>$data['activities']->toArray()])
        @include('partials.fields', ['type'=>'textarea','label'=>'Descripción Ocupación',   'id'=>'occupation_description', 'required'=>1,'value'=>$client->occupation_description])
        @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono de domicilio',   'id'=>'phone_number_home',      'required'=>1,'value'=>$client->phone_number_home, 'icon'=>'icon-phone'])
        @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono celular',        'id'=>'phone_number_mobile',    'required'=>0,'value'=>$client->phone_number_mobile, 'icon'=>'icon-phone'])
        @include('partials.fields', ['type'=>'text',    'label'=>'Email',                   'id'=>'email',                  'required'=>0,'value'=>$client->email, 'icon'=>'icon-mail5'])
    </div>
@endif

@if($client->type == 'L' || old('type', $client->type) == 'L' )
    <div class="col-xs-12 col-md-6">
        @include('partials.fields', ['type'=>'text', 'label'=>'Nombre o Razón Social', 'id'=>'first_name','required'=>1,'value'=>$client->first_name])
        @if($form == 'create')
            @include('partials.fields', ['type'=>'text',    'label'=>'NIT',             'id'=>'dni',       'required'=>1,'value'=>$client->dni])
            @include('partials.fields', ['type'=>'combo',   'label'=>'Nacionalidad',    'id'=>'extension', 'required'=>1,'value'=>$client->extension,'array'=>$data['cities']['CI']->toArray()])
        @endif
        @include('partials.fields', ['type'=>'date',    'label'=>'Fecha de Constitución',   'id'=>'birthdate',              'required'=>1,'value'=>$client->birthdate])        
        @include('partials.fields', ['type'=>'textarea','label'=>'Dirección Principal',     'id'=>'home_address',           'required'=>1,'value'=>$client->home_address])
        @include('partials.fields', ['type'=>'textarea','label'=>'Dirección Sucursal',      'id'=>'business_address',       'required'=>1,'value'=>$client->business_address])
    </div>
    <div class="col-xs-12 col-md-6">
        @include('partials.fields', ['type'=>'combo',   'label'=>'Gran Actividad NIT',      'id'=>'ad_activity_id',         'required'=>1,'value'=>$client->ad_activity_id,'array'=>$data['activities']->toArray()])
        @include('partials.fields', ['type'=>'textarea','label'=>'Actividad principal NIT', 'id'=>'occupation_description', 'required'=>1,'value'=>$client->occupation_description])
        @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono Fijo 1',         'id'=>'phone_number_home',      'required'=>1,'value'=>$client->phone_number_home, 'icon'=>'icon-phone'])
        @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono Fijo 2',         'id'=>'phone_number_mobile',    'required'=>0,'value'=>$client->phone_number_mobile, 'icon'=>'icon-phone'])
        @include('partials.fields', ['type'=>'text',    'label'=>'Email',                   'id'=>'email',                  'required'=>0,'value'=>$client->email, 'icon'=>'icon-mail5'])
    </div>
@endif