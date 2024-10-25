@var $url = realpath(__DIR__.'/../../../resources/views/cert/ec/'.$query_header->template.'.blade.php')
@if((boolean)$query_header->cert_active==true)
    @if(file_exists($url))
        @include('cert.'.$code_product.'.'.$query_header->template)
    @else
        @include('errors.503',['message'=>'El certificado Ecoseguro no puede ser procesado'])
    @endif
@else
    @include('errors.503',['message'=>'El certificado Ecoseguro no puede ser procesado'])
@endif