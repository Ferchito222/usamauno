/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * funcion carga modal por ajax
 * @param {type} id_header
 * @param {type} tokken
 * @param {type} url
 * @param {post} post
 * @param {type} type
 * @returns {undefined}
 */
function cargaModal(id_header, tokken, url, post, type, aux) {
    var _def;
    if (aux)
        _def = aux;
    else
        _def = 0;

    $.ajax({
        url: url,
        type: post,
        data: {
            type: type,
            id_header: id_header,
            _token: tokken,
            aux: _def
        },
        dataType: 'JSON',
        beforeSend: function () {
            $("#respuesta").html('Buscando cliente...');
        },
        error: function () {
            $("#respuesta").html('<div> Ha surgido un error. </div>');
        },
        success: function (respuesta) {
            //console.log(respuesta.template_cert);
            if (respuesta) {
                $("#respuesta").html(respuesta.template_cert);
            } else {
                $("#respuesta").html('<div> No hay ningún cliente con ese id. </div>');
            }
        }
    });
}
function listInsured(url, post, id_header) {
    /**console.log('url-->'+url);
     console.log('post-->'+post);
     console.log('id_header-->'+id_header);/**/
    $.ajax({
        url: url,
        type: post,
        data: {
            id_header: id_header
        },
        dataType: 'JSON',
        beforeSend: function () {
            $("#content_insured").html('Buscando Detalle...');
        },
        error: function () {
            $("#content_insured").html('<div> Cargando informaci&oacute;n... </div>');
        },
        success: function (respuesta) {
            //console.log(respuesta.template_cert);
            if (respuesta) {
                $("#content_insured").html(respuesta.template);
            } else {
                $("#content_insured").html('<div> No existe detalle. </div>');
            }
        }
    });
}
/**
 * Funcion retornacontenido al modal con ajax
 * @param {type} url ruta de ejecucion de una funcion
 * @param {type} method puede ser GET - POST
 * @returns {undefined}
 */
function returnContent(url, method) {
    $.ajax({
        url: url,
        type: method,
        dataType: 'JSON',
        beforeSend: function () {
            $("#respuesta").html('Buscando contenido...');
        },
        error: function () {
            $("#respuesta").html('<div> Ha surgido un error. </div>');
        },
        success: function (respuesta) {
            if (respuesta) {
                // respuesta ajax
                $("#respuesta").html(respuesta.template);
            } else {
                $("#respuesta").html('<div> No hay ningún cliente con ese id. </div>');
            }
        }
    });
}

/**
 * funcion imprime modal
 * @param {type} idDiv
 * @returns {undefined}
 */
function printSelec(idDiv) {
    var ficha = document.getElementById(idDiv);
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write(ficha.innerHTML);
    ventimp.document.close();
    ventimp.print();
    ventimp.close();
    /* validacion ultimo paso, tickeado despues de imprimir*/
    /**if($('#last_level').class()=='current'){
       $('#last_level').removeClass('current'); 
       $('#last_level').addClass('first done');
    }/**/
}

/**
 * funcion retorna mensaje de exito al registro
 * @param {type} text
 * @returns {undefined}
 */
function messageAction(key, text) {

    if (key == 'succes') {
        $.jGrowl(text, {
            header: 'Regístro',
            life: 10000,
            theme: 'alert-styled-left alert-arrow-left border-lg alpha-teal text-teal-900'
        });
    } else if (key == 'info') {
        $.jGrowl(text, {
            header: 'Información',
            life: 10000,
            theme: 'alert-bordered alert-styled-left alert-info'
        });
    } else if (key == 'error') {
        $.jGrowl(text, {
            header: 'Error',
            life: 10000,
            theme: 'alert-bordered alert-styled-left alert-danger'
        });
    }
}

// validacion confirm
var FormGralF = {
    textDelConfirmDef: '¿Esta seguro de eliminar el registro?',
    deleteElement: function (url, text) {
        "use strict";
        var title;

        if (text.length > 0) {
            text = text;
            title = 'Informaci&oacute;n';
        } else {
            text = this.textDelConfirmDef;
            title = 'Eliminar registro';
        }
        $('#md-colored .modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary" onclick="window.location = \'' + url + '\'" data-dismiss="modal">Aceptar</button>')
        $('#md-colored .modal-body #title_alert').html(text);
        $('#md-colored .modal-header #info_alert').html(title);
        $('#md-colored').modal();
    },
};

// traduccion mensaje de error jQuery Validation
$(function() {
    $.extend($.validator.messages,
            {
                required: "Este campo es requerido.",
                remote: "Please fix this field.",
                email: "Por favor, introduce una dirección de correo electrónico válida.",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date (ISO).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "Please enter the same value again.",
                accept: "Please enter a value with a valid extension.",
                maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
                minlength: jQuery.validator.format("Please enter at least {0} characters."),
                rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
                range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
                min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
            });
});


function validaFormAjax(){
    
}