@if($form == 'create')
    <input type="hidden" value="{{ old('code', $client->code) }}" name="code">
    <input type="hidden" value="{{ old('operations', empty($client->operations) ? json_encode([]) : $client->operations) }}" name="operations">
@endif
<input type="hidden" value="{{ old('type', $client->type) }}" name="type">

@if($client->type == 'N' || old('type', $client->type) == 'N' )
    <div class="col-xs-12 col-md-6">
        @include('partials.fields', ['type'=>'text','label'=>'Nombres','id'=>'first_name','required'=>1,'value'=>$client->first_name])
        @include('partials.fields', ['type'=>'text','label'=>'Ap. Paterno','id'=>'last_name','required'=>1,'value'=>$client->last_name])
        @include('partials.fields', ['type'=>'text','label'=>'Ap. Materno','id'=>'mother_last_name','required'=>0,'value'=>$client->mother_last_name])
        @include('partials.fields', ['type'=>'text','label'=>'Ap. de casada','id'=>'married_name','required'=>0,'value'=>$client->married_name])
        @if($form == 'create')
            @include('partials.fields', ['type'=>'combo2','label'=>'Tipo de documento','id'=>'document_type', 'required'=>1,'value'=>$client->document_type,'array'=>$data['document_type']->toArray()])
            @include('partials.fields', ['type'=>'text','label'=>'Documento de identidad','id'=>'dni','required'=>1,'value'=>$client->dni])
            @include('partials.fields', ['type'=>'text','label'=>'Complemento','id'=>'complement','required'=>0,'value'=>$client->complement])
            @include('partials.fields', ['type'=>'combo','label'=>'Ext. Documento de identidad','id'=>'extension', 'required'=>1,'value'=>$client->extension,'array'=>$data['cities']['CI']->toArray()])
        @endif
        @include('partials.fields', ['type'=>'date','label'=>'Fecha de nacimiento','id'=>'birthdate','required'=>1,'value'=>$client->birthdate])    
        @include('partials.fields', ['type'=>'combo2','label'=>'Estado Civil','id'=>'civil_status', 'required'=>1,'value'=>$client->civil_status,'array'=>$data['civil_status']->toArray()])
        @if($form == 'edit')
            @include('partials.fields', ['type'=>'text','label'=>'Localidad','id'=>'locality','required'=>0,'value'=>$client->locality])
        @endif
        @include('partials.fields', ['type'=>'text','label'=>'País/Nacionalidad','id'=>'country','required'=>1,'value'=>$client->country])
        @include('partials.fields', ['type'=>'text','label'=>'Zona','id'=>'zone','required'=>1,'value'=>$client->zone])
    </div>
    <div class="col-xs-12 col-md-6">
        @include('partials.fields', ['type'=>'textarea','label'=>'Dirección','id'=>'home_address','required'=>1,'value'=>$client->home_address])
        @if($form == 'edit')
            @include('partials.fields', ['type'=>'text','label'=>'Número de domicilio','id'=>'home_number','required'=>1,'value'=>$client->home_number])
            @include('partials.fields', ['type'=>'combo2','label'=>'Avenida o Calle','id'=>'avenue_street', 'required'=>1,'value'=>$client->avenue_street,'array'=>$data['avenue_street']->toArray()])
            @include('partials.fields', ['type'=>'text','label'=>'Telef. Oficina','id'=>'phone_number_office', 'required'=>0,'value'=>$client->phone_number_office, 'icon'=>'icon-phone'])
        @endif
        @include('partials.fields', ['type'=>'textarea','label'=>'Dirección laboral','id'=>'business_address','required'=>0,'value'=>$client->business_address])
        @include('partials.fields', ['type'=>'combo','label'=>'Ocupación','id'=>'ad_activity_id', 'required'=>1,'value'=>$client->ad_activity_id,'array'=>$data['activities']->toArray()])
        @include('partials.fields', ['type'=>'textarea','label'=>'Descripción de actividad','id'=>'occupation_description','required'=>1,'value'=>$client->occupation_description])
        @include('partials.fields', ['type'=>'text','label'=>'Teléfono de domicilio','id'=>'phone_number_home', 'required'=>1,'value'=>$client->phone_number_home, 'icon'=>'icon-phone'])
        @include('partials.fields', ['type'=>'text','label'=>'Teléfono celular','id'=>'phone_number_mobile', 'required'=>0,'value'=>$client->phone_number_mobile, 'icon'=>'icon-phone'])
        @include('partials.fields', ['type'=>'text','label'=>'Correo electónico','id'=>'email', 'required'=>0,'value'=>$client->email, 'icon'=>'icon-mail5'])
        @if($form == 'create')
            @include('partials.fields', ['type'=>'combo2','label'=>'Género','id'=>'gender', 'required'=>1,'value'=>$client->gender,'array'=>$data['gender']->toArray()])
        @endif
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