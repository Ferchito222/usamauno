<div id="prueba_modal" class="modal fade">
    <div class="modal-dialog" style="width: 825px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title main-title"></h6>
            </div>
            <div class="modal-body" id="respuesta">
                <p>One fine body&hellip;</p>
            </div>
            <hr />
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    /*FUNCTION EASY LOADING*/
    function easyLoading (element, theme, show) {
        if (show) {
            $(element).loading({
                theme: theme,                  //light
                message: 'Por favor espere...'
            });
        }

        if (! show) {
            $(element).loading('stop');
        }
    }
</script>