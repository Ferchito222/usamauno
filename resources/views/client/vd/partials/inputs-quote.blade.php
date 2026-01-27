@if($form == 'create')
    <input type="hidden" value="{{ old('code', $client->code) }}" name="code">
    <input type="hidden" value="{{ (isset($detail_id))?1:0 }}" name="edition">
    <input type="hidden" value="{{ old('operations', empty($client->operations) ? json_encode([]) : $client->operations) }}" name="operations">
@endif
<input type="hidden" value="{{ old('type', $client->type) }}" name="type">

<div class="col-xs-12 col-md-6">
    @if($form == 'create')
        @include('partials.fields', ['type'=>'text', 'label'=>'Nombres', 'id'=>'first_name','required'=>1,'value'=>$client->first_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido Paterno',    'id'=>'last_name',          'required'=>0,'value'=>$client->last_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido Materno',    'id'=>'mother_last_name',   'required'=>0,'value'=>$client->mother_last_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido de Casada',  'id'=>'married_name',       'required'=>0,'value'=>$client->married_name])
        @include('partials.fields', ['type'=>'combo2','label'=>'Género','id'=>'gender','required'=>1,'value'=>$client->gender,'array'=>$data['gender']->toArray()])
        @include('partials.fields', ['type'=>'text',    'label'=>'C.I.',        'id'=>'dni',            'required'=>1,'value'=>$client->dni])
        @include('partials.fields', ['type'=>'text',    'label'=>'Complemento', 'id'=>'complement',     'required'=>0,'value'=>$client->complement])
        @include('partials.fields', ['type'=>'combo',   'label'=>'Extensión',   'id'=>'extension',      'required'=>1,'value'=>$client->extension,'array'=>$data['cities']['CI']->toArray()])
        @include('partials.fields', ['type'=>'date','label'=>'Fecha de nacimiento','id'=>'birthdate','required'=>1,'value'=>$client->birthdate])    
        @include('partials.fields', ['type'=>'text', 'label'=>'Lugar de Nacimiento', 'id'=>'birth_place', 'required'=>1,'value'=>$client->birth_place])
    @elseif($form == 'edit_issue')
        @include('partials.fields', ['type'=>'label', 'label'=>'Nombres', 'value'=>$client->first_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Apellido Paterno', 'value'=>$client->last_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Apellido Materno', 'value'=>$client->mother_last_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Apellido de Casada', 'value'=>$client->married_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Género','value'=>config('base.client_genders.'.$client->gender)])
        @include('partials.fields', ['type'=>'label', 'label'=>'C.I.', 'value'=>$client->dni])
        @include('partials.fields', ['type'=>'label', 'label'=>'Complemento', 'value'=>$client->complement])
        @include('partials.fields', ['type'=>'label', 'label'=>'Extensión', 'value'=>$client->extension,'array'=>$data['cities']['CI']->toArray()])
        @include('partials.fields', ['type'=>'label', 'label'=>'Fecha de nacimiento', 'value'=>$client->birthdate])    
        @include('partials.fields', ['type'=>'label', 'label'=>'Lugar de Nacimiento', 'value'=>$client->birth_place])
    @endif
    @include('partials.fields', ['type'=>'combo', 'label'=>'Avenida/Calle', 'id'=>'avenue_street', 'required'=>0,'value'=>$client->avenue_street, 'array'=>$data['avenue_street']->toArray()])
    @include('partials.fields', ['type'=>'textarea','label'=>'Dirección','id'=>'home_address','required'=>1,'value'=>$client->home_address])
    @include('partials.fields', ['type'=>'text',    'label'=>'Número',   'id'=>'home_number',      'required'=>0,'value'=>$client->home_number])
    @include('partials.fields', ['type'=>'text', 'label'=>'Nacionalidad', 'id'=>'country', 'required'=>1,'value'=>$client->country])
</div>
<div class="col-xs-12 col-md-6">
    @include('partials.fields', ['type'=>'text',    'label'=>'Zona',   'id'=>'`zone`',      'required'=>0,'value'=>$client->zone])
    @include('partials.fields', ['type'=>'text',    'label'=>'Ciudad',   'id'=>'`locality`',      'required'=>0,'value'=>$client->locality])
    @include('partials.fields', ['type'=>'text',    'label'=>'Departamento',   'id'=>'`place_residence`',      'required'=>0,'value'=>$client->place_residence])
    @include('partials.fields', ['type'=>'text',    'label'=>'País',   'id'=>'`country`',      'required'=>0,'value'=>$client->country])
    @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono 1',   'id'=>'phone_number_home',      'required'=>1,'value'=>$client->phone_number_home, 'icon'=>'icon-phone'])
    @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono 2',        'id'=>'phone_number_mobile',    'required'=>0,'value'=>$client->phone_number_mobile, 'icon'=>'icon-phone'])
    @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono Oficina',        'id'=>'phone_number_office',    'required'=>0,'value'=>$client->phone_number_office, 'icon'=>'icon-phone'])
    @include('partials.fields', ['type'=>'text',    'label'=>'Email',                   'id'=>'email',                  'required'=>1,'value'=>$client->email, 'icon'=>'icon-mail5'])
    @include('partials.fields', ['type'=>'combo',   'label'=>'Ocupación',               'id'=>'ad_activity_id',         'required'=>1,'value'=>$client->ad_activity_id,'array'=>$data['activities']->toArray()])    
    @include('partials.fields', ['type'=>'textarea','label'=>'¿Cual es su profesion u ocupacion habitual?',   'id'=>'occupation_description', 'required'=>1,'value'=>$client->occupation_description])
    {{--@include('partials.fields', ['type'=>'combo2', 'label'=>'Mano utilizada para escribir y/o firmar', 'id'=>'hand', 'required'=>0,'value'=>$client->hand,'array'=>$data['hands']->toArray()])--}}
    @include('partials.fields', ['type'=>'text',    'label'=>'Lugar donde Trabaja',   'id'=>'workplace',      'required'=>0,'value'=>$client->workplace])
    @include('partials.fields', ['type'=>'text',    'label'=>'Cargo',   'id'=>'position',      'required'=>0,'value'=>$client->position])
    @include('partials.fields', ['type'=>'combo2', 'label'=>'Estado Civil', 'id'=>'civil_status', 'required'=>1,'value'=>$client->civil_status,'array'=>$data['civil_status']->toArray()])
    {{-- @include('partials.fields', ['type'=>'text',    'label'=>'Peso (Kg)',        'id'=>'weight',            'required'=>1,'value'=>$client->weight])
    @include('partials.fields', ['type'=>'text',    'label'=>'Estatura (cm)',        'id'=>'height',            'required'=>1,'value'=>$client->height])
    @include('partials.fields', ['type'=>'text',    'label'=>'Tensión Arterial',        'id'=>'pressure',            'required'=>1,'value'=>$client->pressure]) --}}
    
    @if($form == 'edit_issue')
        @include('partials.fields', ['type'=>'text', 'label'=>'Lugar de Trabajo', 'id'=>'workplace', 'required'=>0,'value'=>$client->workplace])
        @include('partials.fields', ['type'=>'text', 'label'=>'Cargo', 'id'=>'position', 'required'=>0,'value'=>$client->position])
        @include('partials.fields', ['type'=>'date', 'label'=>'Fecha de Ingreso','id'=>'date_admission','required'=>0,'value'=>$client->date_admission])    
        @include('partials.fields', ['type'=>'textarea','label'=>'Dirección Comercial (Dirección Laboral)', 'id'=>'commercial_address', 'required'=>0,'value'=>$client->commercial_address])
        @include('partials.fields', ['type'=>'text', 'label'=>'Zona (Comercial)', 'id'=>'zone', 'required'=>0,'value'=>$client->zone])
        @include('partials.fields', ['type'=>'text', 'label'=>'Nombre Cónyugue (Si corresponde)', 'id'=>'name_spouse', 'required'=>0,'value'=>$client->name_spouse])
        @include('partials.fields', ['type'=>'text', 'label'=>'Referencia Personal', 'id'=>'personal_reference', 'required'=>0,'value'=>$client->personal_reference])
        @include('partials.fields', ['type'=>'combo', 'label'=>'Mano utilizada para escribir y/o firmar', 'id'=>'hand', 'required'=>0,'value'=>$client->hand,'array'=>$data['hands']->toArray()])
    @endif
</div>

