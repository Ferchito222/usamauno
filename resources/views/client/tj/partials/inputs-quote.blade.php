@if($form == 'create')
    <input type="hidden" value="{{ old('code', $client->code) }}" name="code">
    <input type="hidden" value="{{ old('operations', empty($client->operations) ? json_encode([]) : $client->operations) }}" name="operations">
@endif
<input type="hidden" value="{{ old('type', $client->type) }}" name="type">


<div class="col-xs-12 col-md-6">
    @if($form == 'create')
        @include('partials.fields', ['type'=>'text', 'label'=>'Nombres', 'id'=>'first_name','required'=>1,'value'=>$client->first_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido Paterno',    'id'=>'last_name',          'required'=>1,'value'=>$client->last_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido Materno',    'id'=>'mother_last_name',   'required'=>0,'value'=>$client->mother_last_name])
        @include('partials.fields', ['type'=>'text',    'label'=>'Apellido de Casada',  'id'=>'married_name',       'required'=>0,'value'=>$client->married_name])
        @include('partials.fields', ['type'=>'combo2','label'=>'Género','id'=>'gender','required'=>1,'value'=>$client->gender,'array'=>$data['gender']->toArray()])
        @include('partials.fields', ['type'=>'text',    'label'=>'C.I.',        'id'=>'dni',            'required'=>1,'value'=>$client->dni])
        @include('partials.fields', ['type'=>'text',    'label'=>'Complemento', 'id'=>'complement',     'required'=>0,'value'=>$client->complement])
        @include('partials.fields', ['type'=>'combo',   'label'=>'Extensión',   'id'=>'extension',      'required'=>1,'value'=>$client->extension,'array'=>$data['cities']['CI']->toArray()])
        @include('partials.fields', ['type'=>'date','label'=>'Fecha de nacimiento','id'=>'birthdate','required'=>1,'value'=>$client->birthdate])    
        @include('partials.fields', ['type'=>'combo', 'label'=>'Estado Civil', 'id'=>'civil_status', 'required'=>1,'value'=>$client->civil_status,'array'=>$data['civil_status']->toArray()])    
    @elseif($form == 'edit_issue')
        @include('partials.fields', ['type'=>'label', 'label'=>'Nombres', 'value'=>$client->first_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Apellido Paterno', 'value'=>$client->last_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Apellido Materno', 'value'=>$client->mother_last_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Apellido de Casada', 'value'=>$client->married_name])
        @include('partials.fields', ['type'=>'label', 'label'=>'Género','value'=>config('base.client_genders.'.$client->gender)])
        @include('partials.fields', ['type'=>'label', 'label'=>'C.I.', 'value'=>$client->dni])
        @include('partials.fields', ['type'=>'label', 'label'=>'Complemento', 'value'=>$client->complement])
        @include('partials.fields', ['type'=>'label', 'label'=>'Extensión', 'value'=>$client->extension,'array'=>$data['cities']['CI']->toArray()])
        @include('partials.fields', ['type'=>'date','label'=>'Fecha de nacimiento','id'=>'birthdate','required'=>1,'value'=>$client->birthdate])    
        @include('partials.fields', ['type'=>'label', 'label'=>'Lugar de Nacimiento', 'value'=>$client->birth_place])
    @endif
</div>
<div class="col-xs-12 col-md-6">
    @include('partials.fields', ['type'=>'textarea','label'=>'Dirección','id'=>'home_address','required'=>1,'value'=>$client->home_address])
    @include('partials.fields', ['type'=>'combo',   'label'=>'Ocupación', 'id'=>'ad_activity_id', 'required'=>1,'value'=>$client->ad_activity_id,'array'=>$data['activities']->toArray()])    
    @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono Domicilio', 'id'=>'phone_number_home', 'required'=>0,'value'=>$client->phone_number_home, 'icon'=>' icon-phone2'])
    @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono Celular', 'id'=>'phone_number_mobile', 'required'=>1,'value'=>$client->phone_number_mobile, 'icon'=>' icon-phone2'])
    @include('partials.fields', ['type'=>'text',    'label'=>'Teléfono Trabajo', 'id'=>'phone_number_office', 'required'=>0,'value'=>$client->phone_number_office, 'icon'=>' icon-phone2'])
    @include('partials.fields', ['type'=>'text',    'label'=>'Correo Electrónico', 'id'=>'email', 'required'=>1,'value'=>$client->email, 'icon'=>'icon-mail5'])
    
    @if($form == 'edit_issue')
        @include('partials.fields', ['type'=>'text', 'label'=>'Lugar de Trabajo', 'id'=>'workplace', 'required'=>0,'value'=>$client->workplace])
        @include('partials.fields', ['type'=>'text',    'label'=>'Peso (Kg)',        'id'=>'weight',            'required'=>0,'value'=>$client->weight])
        @include('partials.fields', ['type'=>'text',    'label'=>'Estatura (cm)',        'id'=>'height',            'required'=>0,'value'=>$client->height])        
        @include('partials.fields', ['type'=>'text', 'label'=>'Zona (Comercial)', 'id'=>'zone', 'required'=>0,'value'=>$client->zone])
    @endif
</div>
