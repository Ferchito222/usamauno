@var $i=1
@foreach($query_quest_cl as $data)
    @if($i==1)
        @var $template = $data->template
        @var $cert_active = $data->cert_active
    @endif
    @var $i=$i+1
@endforeach
@var $url = realpath(__DIR__.'/../../../resources/views/cert/vi/'.$template.'.blade.php')
@if((boolean)$cert_active==true)
    @if(file_exists($url))
        @include('cert.vi.'.$template)
    @else
        @include('errors.503',['message'=>'El certificado Vida no puede ser procesado'])
    @endif
@else
    @include('errors.503',['message'=>'El certificado Vida no puede ser procesado'])
@endif