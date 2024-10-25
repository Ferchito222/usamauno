/**
 * Created by Cliente on 04/10/2016.
 */
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

$(document).ready(function(){

    /*--------------------AJAX VENTANA MODAL EDITAR CERTIFICADO-------------------*/
    $('a[href].open_modal_certificate').click(function(e){
        e.preventDefault();

        var href = $(this).prop('href');
        var id = $(this).prop('id');
        //var product = $(this).attr('rel');
        //alert(rel);
        //alert('href: '+href+'  id: '+id);

        if(id=='edit'){
            $('.main-title').text('Editar Registro');
        }else if(id=='new'){
            $('.main-title').text('Nuevo Registro');
        }

        $.ajax({
            type: 'GET',
            cache: false,
            url: href,
            data: {personid: 873},
            beforeSend: function() {
                easyLoading('body', 'dark', true);
            },
            success: function(response) {
                console.log(response);
                $('#prueba_modal .modal-body').html(response.payload);
                $('#prueba_modal').modal();
            },
            error: function( jqXHR, textStatus, errorThrown ) { // if error occured
                if (jqXHR.status === 0) {
                    alert('Sin conexion: Verificar la red.');
                } else if (jqXHR.status == 404) {
                    alert('Pagina solicitada no se encuentra [404]');
                } else if (jqXHR.status == 500) {
                    alert('Error de servidor interno [500].');
                } else if (textStatus === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (textStatus === 'timeout') {
                    alert('Error de tiempo de espera.');
                } else if (textStatus === 'abort') {
                    alert('Peticion Ajax abortado.');
                } else {
                    alert('Error no capturado: ' + jqXHR.responseText);
                }
            },
            complete: function() {
                easyLoading('body', '', false);
            }
        });
        /**/
    });

    $(".activar").mouseover(function(){
        $(this).find("span").removeClass('label label-success').addClass('label label-default').text("desactivar");
    });
    $(".activar").mouseout(function(){
        $(this).find("span").removeClass('label label-default').addClass('label label-success').text("Activo");
    });

    $(".desactivar").mouseover(function(){
        $(this).find("span").removeClass('label label-default').addClass('label label-success').text("activar");
    });
    $(".desactivar").mouseout(function(){
        $(this).find("span").removeClass('label label-success').addClass('label label-default').text("Inactivo");
    });

});
