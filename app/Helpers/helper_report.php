<?php
function departamento($depto)
{
    switch ($depto){
        case 'lapaz': return 'La Paz';
            break;
        case 'oruro': return 'Oruro';
            break;
        case 'potosi': return 'Potosi';
            break;
        case 'tarija': return 'Tarija';
            break;
        case 'chuquisaca': return 'Sucre';
            break;
        case 'santacruz': return 'Santa Cruz';
            break;
        case 'beni': return 'Beni';
            break;
        case 'pando': return 'Pando';
            break;
        case 'cochabamba': return 'Cochabamba';
            break;
    }
}

function fecha($month, $year){
    switch ($month):
        case 1: case 3: case 5: case 7: case 8: case 10: case 12: return 31;
        case 4: case 6: case 9: case 11: return 30;
        case 2:
            $val=bisiesto($year);
            return $val;
    endswitch;
}

function bisiesto($year){
    if(($year % 4 == 0) && (($year % 100 != 0) || ($year % 400 == 0))){
        return 29;
    }else{
        return 28;
    }
}

function mes($mo){
    switch ($mo):
        case 1: return 'Enero'; case 2: return 'Febrero'; case 3: return 'Marzo'; case 4: return 'Abril';
        case 5: return 'Mayo'; 	case 6: return 'Junio'; case 7: return 'Julio';	case 8: return 'Agosto';
        case 9: return 'Septiembre'; case 10: return 'Octubre';	case 11: return 'Noviembre'; case 12: return 'Diciembre';
    endswitch;
}

function cuenta_ciudad($c){
    switch ($c):
        case '11': return 'CHUQUISACA'; case '20': return 'LA PAZ'; case '22': return 'EL ALTO';
        case '30': return 'COCHABAMBA';	case '40': return 'ORURO'; 	case '50': return 'POTOSI';
        case '60': return 'TARIJA';	case '70': return 'SANTA CRUZ';	case '80': return 'BENI';
        case '90': return 'PANDO';
    endswitch;
}

function estado($es){
    switch ($es):
        case 'Pendiente': return 'PEN'; case 'Vigente': return 'VIG'; case 'Impago': return 'IMP';case 'Cancelado': return 'CAN';
    endswitch;
}

function tipo_pago($tp){
    switch ($tp):
        case 'DA': return 'M'; case 'CO': return 'A';
    endswitch;
}

function estado_civil($ec){
    switch ($ec):
        case 'S': return 'SOL'; case 'C': return 'CAS';
    endswitch;
}

function tipo_operacion($to){
    switch ($to):
        case 'Individual': return 'IND'; case 'Mancomunado': return 'MAN'; case 'Banca Comunal': return 'BCO';
    endswitch;
}

function moneda_op($m){
    switch ($m):
        case 'BS': return 'BOB'; case 'USD': return 'USD';
    endswitch;
}

function prod_ecoseguro($pago, $cli){
    if($pago == 'DA') {
        return 'ECOCLIEMEN';
    }elseif($cli == FALSE){
        return 'ECONOCLI';
    }else{
        return 'ECOCLIENU';
    }
}

function estado_cobro($do, $dn, $dt){
    if($dt >= $do and $dt <= $dn){
        return 'Pagado';
    }else{
        return 'Pendiente';
    }
}
