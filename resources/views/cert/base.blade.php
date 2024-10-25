@if($type=='IMPR')
    <a href="javascript:void(0)" class="link-cert" rel="print" id="send-print" title="Imprimir" target="_blank">
        <i class="fa fa-print fa-3x text-success"></i>
    </a>
    &nbsp;&nbsp;
    <a href="{{ route('create_modal_slip', ['id_retailer_product'=>$id_retailer_product, 'id_header'=>$id_header, 'text'=>$text, 'type'=>'PDF']) }}" class="link-cert" target="_blank" title="Generar pdf">
        <i class="fa fa-file-pdf-o fa-3x text-primary"></i>
    </a>
    <hr/>
@endif

<div class="print">
    @if($text=='slip')
        @include('cert.'.$code_product.'.certificate_slip')
    @elseif($text=='issuance')
        @include('cert.'.$code_product.'.certificate_issuance')
    @elseif($text=='print_all')
        @include('cert.'.$code_product.'.certificate_slip')
        <page><div style="page-break-before: always;">&nbsp;</div></page>
        @include('cert.'.$code_product.'.certificate_issuance')
        @if($code_product=='de')
            @if($sub_product_code=='vi' && count($query_quest_cl)>0)
                <page><div style="page-break-before: always;">&nbsp;</div></page>
                @include('cert.'.$sub_product_code.'.certificate_issuance')
            @endif
        @endif
    @endif
</div>

<style type="text/css">
    @page { margin: 0 0 0 10px; }
</style>

<script type="text/javascript">
    $(document).ready(function(){
        $('#send-print').click(function(){
            $('#finish').removeClass('current');
            $('#finish').addClass('first done');
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = { mode : mode, popClose : close};
            var rel = $(this).prop('rel');
            $("div."+rel).printArea(options);
        });
    });
</script>