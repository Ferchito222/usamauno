/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Campos requeridos
var required = {
    'matter_insured': 'Materia',
    'matter_description': 'Descripción',
    'number': 'Número',
    'use': 'Uso',
    'construction_value': 'Valor Construcción',
    //'land_value': 'Valor de Terreno',
    'insured_value': 'Valor Asegurado',
    'locality': 'Ciudad o Localidad'
}

// Campos solo NUmeros    
var number = {
    'number': 'Número',
    'construction_value': 'Valor Construcción',
    //'land_value': 'Valor de Terreno',
    'insured_value': 'Valor Asegurado'
}

// Campos solo texto    
var text = {
    'locality': 'Ciudad o Localidad'
}

// Validacion formulario REGISTRO DE RIESGOS AJAX
$('#insured_form').on('submit', function(event) {
    var keyErr = requeridos();
    if (keyErr === 0) {
        event.preventDefault();
        $('button[type="submit"]').prop('disabled', true);
        var formData = {
            _token: $('#_token').val(),
            id_header: $('#id_header').val(),
            id_detail: $('#id_detail').val(),
            matter_insured: $('#matter_insured').val(),
            matter_description: $('#matter_description').val(),
            number: $('#number').val(),
            use: $('#use').val(),
            construction_value: $('#construction_value').val(),
            //land_value: $('#land_value').val(),
            insured_value: $('#insured_value').val(),
            city: $('#city').val(),
            zone: $('#zone').val(),
            locality: $('#locality').val(),
            address: $('#address').val()
        }
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            success: function(data) {
                $('.list_content').click();
                $('#close_modal').click();
                reloadPage();
            }
        })
    }
    return false;
});

$(document).ready(function() {
    validateInsuredValue();
    soloNumeros();
    soloLetras();
});

// valida combo box valor asegurado
$('#matter_insured').change(function() {
    validateInsuredValue();
});

$('#matter_insured').change(function() {
    valueUse();
});

// FIN Validacion

function requeridos() {
    var error = 0;
    $.each(required, function(key, value) {
        if ($('#' + key + '').val() == '' || $('#' + key + '').val() == 0) {
            $('.' + key + '_msg').html('Campo ' + value + ' es requerido');
            if (key == 'construction_value' || key == 'land_value') {
                if ($('#matter_insured').val() == 'PR')
                    error = error + 1;
            } else {
                if (key == 'insured_value') {
                    if (!$('#matter_insured').val == 'PR')
                        error = error + 1;
                } else {
                    error = error + 1;
                }
            }
            console.log('ingresando-->' + value);
        } else {
            $('.' + key + '_msg').html('');
            error = error;
        }
    });
    return error;
}

/**
 * funcion valida visualizacion campos valor asegurado del form de registro de riesgos
 * @returns {undefined}
 */
function validateInsuredValue() {
    if ($('#matter_insured').val() == 'PR') {
        $('.valor_construccion').show();
        $('.valor_de_terreno').show();
        $('.valor_asegurado').hide();
    } else {
        $('.valor_construccion').hide();
        $('.valor_de_terreno').hide();
        $('.valor_asegurado').show();
    }
}

/**
 * funcion valida campo solo nuemros
 * @returns {undefined}
 */
function soloNumeros() {
    $.each(number, function(key, value) {
        $('#' + key + '').keyup(function() {
            if ($('#' + key + '').val() != /[^0-9]/g) {
                this.value = (this.value + '').replace(/[^0-9]/g, '');
                //$('.' + key + '_msg').html('Ingrese solo N&uacute;meros');
                $('.' + key + '_msg').html('<label id="bill_name-error" class="validation-error-label validation-valid-label" for="bill_name">Correcto.</label>');
            }
        });
    });
}

/**
 * Funcion valida campo solo letras
 * @returns {undefined}
 */
function soloLetras() {
    $.each(text, function(key, value) {
        $('#' + key + '').keypress(function(env) {
            if ((env.charCode < 97 || env.charCode > 122)//letras mayusculas
                    && (env.charCode < 65 || env.charCode > 90) //letras minusculas
                    && (env.charCode != 45) //retroceso
                    && (env.charCode != 241) //ñ
                    && (env.charCode != 209) //Ñ
                    && (env.charCode != 32) //espacio
                    && (env.charCode != 225) //á
                    && (env.charCode != 233) //é
                    && (env.charCode != 237) //í
                    && (env.charCode != 243) //ó
                    && (env.charCode != 250) //ú
                    && (env.charCode != 193) //Á
                    && (env.charCode != 201) //É
                    && (env.charCode != 205) //Í
                    && (env.charCode != 211) //Ó
                    && (env.charCode != 218) //Ú
                    && (env.charCode != 0) //reset

                    ) {
                //$('.' + key + '_msg').html('Ingrese solo Letras');
                $('.' + key + '_msg').html('<label id="bill_name-error" class="validation-error-label validation-valid-label" for="bill_name">Correcto.</label>');
                return false;
            }

        });
    });
}

/**
 * funcion procesa valor para el combo USO del formulario registro de riesgo Multiriesgo
 * @returns {undefined}
 */
function valueUse() {
    $('#use option[value="ID"]').remove();
    $('#use option[value="IP"]').remove();
    $('#use option[value="OT"]').remove();

    if ($('#matter_insured').val() == 'PR') {
        $('#use option[value="OT"]').remove();
        if ($('#matter_insured').val() != 'ID')
            $('#use').append('<option value="ID">Inmueble Domiciliario</option>');

        if ($('#matter_insured').val() != 'IP')
            $('#use').append('<option value="IP">Inmueble Industrial</option>');
    } else {
        if ($('#matter_insured').val() != 'OT') {
            $('#use').append('<option value="OT">Otros</option>');
            $('#use option[value="ID"]').remove();
            $('#use option[value="IP"]').remove();
        }
    }
}
