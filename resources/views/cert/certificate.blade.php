@if($format === 'print')
    <a href="javascript:void(0)" class="link-cert print" title="Imprimir" target="_blank">
        <i class="fa fa-print fa-3x text-success"></i>
    </a>
    <a href="{{ $route }}"
       class="link-cert" target="_blank" title="PDF" style="margin-left: 15px;">
        <i class="fa fa-file-pdf-o fa-3x text-primary"></i>
    </a>
    <hr>
@endif
<div id="print">
    <style type="text/css">
        @page {
            margin: 0 0 0 10px;
        }
    </style>

    {!! $view !!}
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.print').click(function () {
            var mode = 'iframe';
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            var rel = $(this).prop('rel');
            $('div#print').printArea(options);
        });
    });
</script>