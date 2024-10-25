<?php

namespace Sibas\Http\Controllers\Elp;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Sibas\Entities\Elp\Collection;
use Sibas\Entities\Elp\Header;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;

class CiaSettlementController extends Controller
{

    public function client()
    {
        $m=date('m') - 1;
        $y=date('Y');
        if($m <= 0){$y = $y - 1; $m = 12 + $m; }

        $d=fecha($m,$y);

        $date_old = $y.'-'.$m.'-01';
        $date_cur = $y.'-'.$m.'-'.$d;

        //$date_old ='2016-11-01';
        //$date_cur ='2016-11-30';

        $cli = Collection::with(['header.detail.client','header.detail.beneficiary','header.state','header.user.agency','header.user.city','header.coverage','header' => function($q){
            $q -> whereHas('detail', function($q1){
                $q1 -> where('client_eco', TRUE);
            });
        }])->whereHas('header.detail', function($q2){
            $q2 -> where('client_eco', TRUE);
        })->whereHas('header',function($q3){
            $q3 -> where('issued',TRUE);
        })->join('op_elp_headers', 'op_elp_headers.id', '=', 'op_elp_collections.op_elp_header_id')
          ->whereNotNull(DB::raw('if(charged = false, op_elp_headers.canceled = false, op_elp_headers.issued = true)'))
          ->where(function($q4) use($date_old, $date_cur){
            $q4->whereBetween('fee_date', [$date_old, $date_cur])
                ->orWhereBetween('transaction_date', [$date_old, $date_cur]);
        })->get();

        $txt='';
        $sum = 0;

        foreach ($cli as $cliente){
            /*
             * generamos el registro
             */

            $id_emision = $cliente -> header -> issue_number;
            $no_poliza = $cliente -> header -> policy_number;
            $fecha_cre = $cliente -> header -> created_at -> format('Y-m-d');
            $fecha_emi = $cliente -> header -> date_issue -> format('Y-m-d');
            $tipo_pago = $cliente -> header -> detail -> payment_method;
            $no_cuenta = $cliente -> header -> detail -> operation_number;
            $moneda_op = $cliente -> header -> detail -> currency;
            $moneda = $cliente -> header -> detail -> currency;
            $cod_agenda = $cliente -> header -> detail -> client -> code;
            $paterno = $cliente -> header -> detail -> client -> last_name;
            $materno = $cliente -> header -> detail -> client -> mother_last_name;
            $casada = $cliente -> header -> detail -> client -> married_name;
            $nombre = $cliente -> header -> detail -> client -> first_name;
            $ci = $cliente -> header -> detail -> client -> dni;
            $extension = $cliente -> header -> detail -> client -> extension;
            $tipo_doc = $cliente -> header -> detail -> client -> document_type;
            $fecha_nac = $cliente -> header -> detail -> client -> birthdate;
            $sexo = $cliente -> header -> detail -> client -> gender;
            $ciudad = $cliente -> header -> detail -> client -> place_residence;
            $barrio = $cliente -> header -> detail -> client -> locality;
            $dir_dom = $cliente -> header -> detail -> client -> home_address;
            $tel_dom = $cliente -> header -> detail -> client -> phone_number_home;
            $ocupacion = $cliente -> header -> detail -> client -> ocupation_description;
            $lugar_nac = $cliente -> header -> detail -> client -> birth_place;
            $estado_civil = $cliente -> header -> detail -> client -> civil_status;
            $ben_nombre = $cliente -> header -> detail -> beneficiary -> first_name;
            $ben_paterno = $cliente -> header -> detail -> beneficiary -> last_name;
            $ben_materno = $cliente -> header -> detail -> beneficiary -> mother_last_name;
            $ben_parentesco = $cliente -> header -> detail -> beneficiary -> relationship;
            $estado = $cliente -> header -> state -> state;
            $tipo_operacion = $cliente -> header -> coverage -> name;
            $departamento = $cliente -> header -> user -> city -> name;
            $agencia = $cliente -> header -> user -> agency -> name;
            $usuario = $cliente -> header -> user -> full_name;
            $monto_trans = $cliente -> transaction_amount;
            $fecha_trans = $cliente -> transaction_date;

            if($fecha_trans == null){
                $fecha_trans = '0000-00-00';
                $monto_trans = '0.00';
            }else{
                $fecha_trans = $fecha_trans -> format('Y-m-d');
                $sum = $sum + $monto_trans;
            }

            $saldo = '0.00'; //Completar
            $cargo = 'Cajero'; //Completar
            $fecha_can = '0000-00-00';
            $cliente = '1';
            $beneficiario = $ben_nombre.' '.$ben_paterno.' '.$ben_materno;

            $estado_cobro = estado_cobro($date_old,$date_cur,$fecha_trans);

            $txt.=$cod_agenda.'|'.$id_emision.'|'.$no_poliza.'|'.$paterno.'|'.$materno.'|'.$casada.'|'.$nombre.'|'.$ci.'|'.$extension.'|'.$tipo_doc.'|'.$fecha_nac.'|'.$sexo.'|'.$no_cuenta.'|'.moneda_op($moneda_op).'|'.tipo_operacion($tipo_operacion).'|'.$ciudad.'|'.$barrio.'|'.$dir_dom.'|'.$tel_dom.'|'.estado($estado).'|'.prod_ecoseguro($tipo_pago,$cliente).'|'.$departamento.'|'.$agencia.'|'.$fecha_cre.'|'.$usuario.'|'.$beneficiario.'|'.$ben_parentesco.'|'.tipo_pago($tipo_pago).'|'.$fecha_can.'|'.$fecha_emi.'| | | |'.$ocupacion.'|'.$lugar_nac.'|'.estado_civil($estado_civil).'|'.$monto_trans.'|'.$moneda.'|'.$estado_cobro.'|'.$saldo.'|'.$fecha_trans.'|'.$cargo.chr(13).chr(10);

        }


        $path = storage_path('exports');

        $directory = $path.'/Ecoseguro_cliente_'.mes($m).' '.$y.'.txt';

        File::put($directory, $txt);

        return($txt);
    }


    public function not_client()
    {
        $m=date('m') - 1;
        $y=date('Y');
        if($m <= 0){$y = $y - 1; $m = 12 + $m; }

        $d=fecha($m,$y);

        $date_old = $y.'-'.$m.'-01';
        $date_cur = $y.'-'.$m.'-'.$d;

        //$date_old ='2016-11-01';
        //$date_cur ='2016-11-30';

        $cli = Collection::with(['header.detail.client','header.detail.beneficiary','header.state','header.user.agency','header.user.city','header.coverage','header' => function($q){
            $q -> whereHas('detail', function($q1){
                $q1 -> where('client_eco', FALSE);
            });
        }])->whereHas('header.detail', function($q2){
            $q2 -> where('client_eco', FALSE);
        })->whereHas('header',function($q3){
            $q3 -> where('canceled',FALSE);
        })->WhereBetween('transaction_date', [$date_old, $date_cur])
          ->get();

                $txt='';
                $sum = 0;

        foreach ($cli as $cliente){
            /*
            * generamos el registro
            */

            $id_emision = $cliente -> header -> issue_number;
            $no_poliza = $cliente -> header -> policy_number;
            $fecha_cre = $cliente -> header -> created_at -> format('Y-m-d');
            $fecha_emi = $cliente -> header -> date_issue -> format('Y-m-d');
            $tipo_pago = $cliente -> header -> detail -> payment_method;
            $voucher = $cliente -> header -> detail -> voucher;
            $paterno = $cliente -> header -> detail -> client -> last_name;
            $materno = $cliente -> header -> detail -> client -> mother_last_name;
            $casada = $cliente -> header -> detail -> client -> married_name;
            $nombre = $cliente -> header -> detail -> client -> first_name;
            $ci = $cliente -> header -> detail -> client -> dni;
            $extension = $cliente -> header -> detail -> client -> extension;
            $tipo_doc = $cliente -> header -> detail -> client -> document_type;
            $fecha_nac = $cliente -> header -> detail -> client -> birthdate;
            $sexo = $cliente -> header -> detail -> client -> gender;
            $ciudad = $cliente -> header -> detail -> client -> place_residence;
            $barrio = $cliente -> header -> detail -> client -> locality;
            $dir_dom = $cliente -> header -> detail -> client -> home_address;
            $tel_dom = $cliente -> header -> detail -> client -> phone_number_home;
            $ocupacion = $cliente -> header -> detail -> client -> ocupation_description;
            $lugar_nac = $cliente -> header -> detail -> client -> birth_place;
            $estado_civil = $cliente -> header -> detail -> client -> civil_status;
            $ben_nombre = $cliente -> header -> detail -> beneficiary -> first_name;
            $ben_paterno = $cliente -> header -> detail -> beneficiary -> last_name;
            $ben_materno = $cliente -> header -> detail -> beneficiary -> mother_last_name;
            $ben_parentesco = $cliente -> header -> detail -> beneficiary -> relationship;
            $estado = $cliente -> header -> state -> state;
            $departamento = $cliente -> header -> user -> city -> name;
            $agencia = $cliente -> header -> user -> agency -> name;
            $usuario = $cliente -> header -> user -> full_name;
            $monto_trans = $cliente -> transaction_amount;
            $cargo = 'Cajero'; //Completar
            $fecha_can = '0000-00-00';
            $cliente = '0';
            $beneficiario = $ben_nombre.' '.$ben_paterno.' '.$ben_materno;

            $sum = $sum + $monto_trans;

            $txt.=$ci.'|'.$id_emision.'|'.$no_poliza.'|'.$voucher.'|'.prod_ecoseguro($tipo_pago,$cliente).'|'.$paterno.'|'.$materno.'|'.
                $casada.'|'.$nombre.'|'.$ci.'|'.$extension.'|'.$tipo_doc.'|'.$fecha_nac.'|'.$sexo.'|'.
                $ciudad.'|'.$barrio.'|'.$dir_dom.'|'.$tel_dom.'|'.estado($estado).'|'.
                $departamento.'|'.$agencia.'|'.$fecha_cre.'|'.$usuario.'|'.$beneficiario.'|'.$ben_parentesco.'|'.tipo_pago($tipo_pago).'|'.
                $fecha_can.'|'.$fecha_emi.'| | | |'.$ocupacion.'|'.$lugar_nac.'|'.estado_civil($estado_civil).'|'.$cargo.chr(13).chr(10);

        }


        $path = storage_path('exports');

        $directory = $path.'/Ecoseguro_No_cliente_'.mes($m).' '.$y.'.txt';

        File::put($directory, $txt);

        return($txt);
    }


    public function anul_client()
    {
        $m=date('m') - 1;
        $y=date('Y');
        if($m <= 0){$y = $y - 1; $m = 12 + $m; }

        $d=fecha($m,$y);

        $date_old = $y.'-'.$m.'-01';
        $date_cur = $y.'-'.$m.'-'.$d;

        //$date_old ='2016-11-01';
        //$date_cur ='2016-11-30';

        $cli = Header::with(['detail','detail.client'])
            ->whereHas('cancellation', function($q1) use($date_old, $date_cur){
                $q1 -> where('reason','like','%Sobrepasa los 60 %')
                    -> whereBetween('created_at', [$date_old, $date_cur]);
          })->where('canceled',true)
            ->get();

        $txt='';

        foreach ($cli as $cliente){
            /*
             * generamos el registro
             */

            $id_emision = $cliente -> issue_number;
            $no_poliza = $cliente -> policy_number;
            $tipo_pago = $cliente -> detail -> payment_method;
            $no_cuenta = $cliente -> detail -> operation_number;
            $cod_agenda = $cliente -> detail -> client -> code;
            $paterno = $cliente -> detail -> client -> last_name;
            $materno = $cliente -> detail -> client -> mother_last_name;
            $casada = $cliente -> detail -> client -> married_name;
            $nombre = $cliente -> detail -> client -> first_name;
            $ci = $cliente -> detail -> client -> dni;
            $extension = $cliente -> detail -> client -> extension;
            $mot_anulado = $cliente -> cancellation -> reason;
            $fecha_anul = $cliente -> cancellation -> created_at -> format('Y-m-d');
            $cliente = '1';

            $txt.=$cod_agenda.'|'.$id_emision.'|'.$no_poliza.'|'.prod_ecoseguro($tipo_pago,$cliente).'|'.$paterno.'|'.$materno.'|'.$casada.'|'.$nombre.'|'.$ci.'|'.$extension.'|'.$no_cuenta.'|Anulado|'.$mot_anulado.'|'.$fecha_anul.chr(13).chr(10);

        }


        $path = storage_path('exports');

        $directory = $path.'/Ecoseguro_anulados_'.mes($m).' '.$y.'.txt';

        File::put($directory, $txt);

        return($txt);
    }

}
