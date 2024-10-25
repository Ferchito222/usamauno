<?php

namespace Sibas\Http\Controllers\Elp;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Sibas\Entities\Agency;
use Sibas\Entities\Elp\Collection;
use Sibas\Entities\Elp\Header;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;

class SettlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function month()
    {
        $m=date('m') - 1;
        $y=date('Y');
        if($m <= 0){$y = $y - 1; $m = 12 + $m; }

        $d=fecha($m,$y);

        $date_old = $y.'-'.$m.'-01';
        //$fecha_ant=date('2015-01-15');
        $date_cur = $y.'-'.$m.'-'.$d;


        $var = Header::with(['detail.client','user.agency','collections' => function($q) use ($date_old,$date_cur){
            $q -> where('charged', true)
               -> whereBetween('transaction_date',[$date_old, $date_cur]);
        }])->whereHas('collections' , function($query){
             $query -> where('charged', '=', true);
         }) ->join('op_elp_details', 'op_elp_headers.id', '=', 'op_elp_details.op_elp_header_id')
             ->whereNotNull(DB::raw('if(op_elp_details.client_eco = false, op_elp_headers.canceled = false, op_elp_headers.issued = true)'))
             ->get();

        //var_dump($var);
        //dd($var);

        $ex = view('elp.settlement',['months'=>$var, 'date'=>mes($m)]);

        Excel::create('Liquidacion '.mes($m), function($excel) use($m, $var){
            // Set the title
            $excel->setTitle('Liquidacion '.mes($m));

            // Chain the setters
            $excel->setCreator('Coboser')
                ->setCompany('Coboser');

            // Call them separately
            $excel->setDescription('Archivo de liquidación');

            $excel->sheet('Liquidacion', function($sheet) use($var, $m){
                  $sheet->loadView('elp.settlement',['months'=>$var, 'date'=>mes($m)]);
            });

        })->store('xls');

        return $ex->render();

    }



    public function agency()
    {
        $m=date('m') - 1;
        $y=date('Y');
        if($m <= 0){$y = $y - 1; $m = 12 + $m; }

        $d=fecha($m,$y);

        $date_old = $y.'-'.$m.'-01';
        $date_cur = $y.'-'.$m.'-'.$d;

        $t[1] = "Ecoproteccion";
        $t[2] = "Ecovida";
        $t[3] = "Ecosalud";
        $t[4] = "Ecosalud Plus";
        $t[5] = "Ecosalud Cartera Antigua";


        $ag[1] = Agency::with(['headers'=> function($q2) use ($date_old,$date_cur){
            $q2-> whereHas('detail', function($q3){
                $q3 -> where('client_eco', true);
            })-> whereHas('collections', function($q5) use ($date_old,$date_cur){
                $q5 -> where('charged', true)
                    ->whereBetween('transaction_date', [$date_old, $date_cur]);
            });

        }])->whereHas('headers.detail', function($q){
            $q ->where('client_eco', TRUE);
        })->whereHas('headers.collections', function($q4) use ($date_old,$date_cur){
            $q4 ->where('charged', true)
                ->whereBetween('transaction_date', [$date_old, $date_cur]);
        })->get();

        //dd($ag[1]);


        $ag[2] = Agency::with(['headers'=> function($q2) use ($date_old,$date_cur){
            $q2-> whereHas('detail', function($q3){
                $q3 -> where('client_eco', false);
            })-> whereHas('collections', function($q5) use ($date_old,$date_cur){
                $q5 -> where('charged', true)
                    ->whereBetween('transaction_date', [$date_old, $date_cur]);
            })->where('canceled',FALSE);

        }])->whereHas('headers.detail', function($q){
            $q ->where('client_eco', FALSE);
        })->whereHas('headers.collections', function($q4) use ($date_old,$date_cur){
            $q4 ->where('charged', true)
                ->whereBetween('transaction_date', [$date_old, $date_cur]);
        })->get();

        //dd($ag[2]);
        $vista = '';
        for($c=1; $c<=2; $c++) {
            //return view('elp.agencies', ['masivo' => $ag, 'titulo' => $t, 'date' => mes($m), 'i' => 1]);

            $xls = view('elp.agencies',['masivo'=>$ag[$c], 'titulo'=>$t[$c], 'date'=>mes($m), 'i'=>1]);
            $vista .= $xls->render();

            Excel::create('Detalle por Agencias  '.$t[$c].' '.mes($m), function($excel) use($m, $ag, $t, $c){
                // Set the title
                $excel->setTitle('Detalle_Agencia '.mes($m).' '.$t[$c]);

                // Chain the setters
                $excel->setCreator('Coboser')
                    ->setCompany('Coboser');

                // Call them separately
                $excel->setDescription('Detalle de Agencias '.$t[$c]);

                $excel->sheet('Detalle '.$t[$c], function($sheet) use($m, $ag, $t, $c){
                    $sheet->loadView('elp.agencies',['masivo'=>$ag[$c], 'titulo'=>$t[$c], 'date'=>mes($m), 'i'=>1]);
                });

            })->store('xls');
        }
        return $vista;
    }



    public function city()
    {
        $m=date('m') - 1;
        $y=date('Y');
        if($m <= 0){$y = $y - 1; $m = 12 + $m; }

        $d=fecha($m,$y);

        $date_old = $y.'-'.$m.'-01';
        $date_cur = $y.'-'.$m.'-'.$d;

        $f[1] = '11';//Chuquisaca
        $f[2] = '20';//La Paz
        $f[3] = '22';//El Alto
        $f[4] = '30';//Cochabamba
        $f[5] = '40';//Oruro
        $f[6] = '50';//Potosi
        $f[7] = '60';//Tarija
        $f[8] = '70';//Santa Cruz
        $f[9] = '80';//Beni
        $f[10] = '90';//Pando

        /*$date_old ='2016-11-01';
        $date_cur ='2016-11-30';*/

        $i=1;
        $ex='';
        $view ='';

        while($i <= 10){

            $ct[$i] = Collection::with(['header'=> function($q) use ($date_old,$date_cur, $f, $i){
                $q-> whereHas('detail', function($q1) use($f,$i){
                    $q1 -> where('operation_number', 'like', $f[$i].'%');
                });
            }])->whereHas('header.detail', function($q2) use($f,$i){
                $q2 -> where('operation_number', 'like', $f[$i].'%');
            })->whereBetween('transaction_date', [$date_old, $date_cur])
                ->get();

            $ct[$i] = $ct[$i]->groupBy(function($item){
                return $item -> transaction_date -> format('Y-m-d');
            });
            //dd($ct[$i]);

            $ex = view('elp.city',['datacity'=>$ct[$i], 'namecity'=>cuenta_ciudad($f[$i])]);
            $view .= $ex->render();
            $i++;
        }
        //return $ex;

        $c = 1;

        Excel::create('Detalle por Sucursales Ecoseguro '.mes($m), function($excel) use($m, $ct, $f, $c){
            // Set the title
            $excel->setTitle('Detalle por Sucursales '.mes($m));

            // Chain the setters
            $excel->setCreator('Coboser')
                ->setCompany('Coboser');

            // Call them separately
            $excel->setDescription('Detalle por Sucursales Ecoseguro');
            while($c <= 10) {
                $excel->sheet(cuenta_ciudad($f[$c]), function($sheet) use($ct, $m, $f, $c){

                    $sheet->loadView('elp.city', ['datacity' => $ct[$c], 'namecity' => cuenta_ciudad($f[$c])]);


                });
                $c++;
            }
        })->store('xls');

        return $view;

    }
}
