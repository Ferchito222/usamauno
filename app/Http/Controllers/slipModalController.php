<?php

namespace Sibas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Sibas\Http\Controllers\PdfController;
use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Illuminate\Support\Facades\Auth;
use Sibas\Entities\User;
use Sibas\Entities\RetailerProduct;
use Sibas\Entities\De\Header;
use Sibas\Entities\De\Detail;
use Sibas\Entities\De\Facultative;

class slipModalController extends Controller
{

    /**
     * @var PdfController
     */
    protected $pdf;

    var $retailer;

    var $retailerProduct;


    public function __construct(PdfController $pdf)
    {
        $this->pdf = $pdf;

        $auth                  = Auth::user();
        $user                  = User::where('id', $auth->id)->first();
        $this->retailer        = $user->retailerUser->retailer;
        $this->retailerProduct = RetailerProduct::where('ad_retailer_id', $this->retailer->id)->where('type',
            'MP')->get();
    }


    /**
     * funcion retorna ajax
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return int
     */
    public function ajaxBuscar(Request $request)
    {
        $var = $this->returnHtmlModal($request->get('type'), $request->get('id_header'), 0, $request->get('aux'));

        if (count($var['cli']) > 0) {
            return response()->json($var['html']);
        }

        return 0;
    }


    /**
     *
     * @param type $type
     * @param type $idHeader
     *
     * @return Response
     */
    public function generaPdf($type, $idHeader, $aux)
    {
        $var = $this->returnHtmlModal($type, decode($idHeader), 1, $aux);
        set_time_limit(0);

        return $this->pdf->create($var['html']['template_cert'], $type);
    }


    public function returnHtmlModal($type, $idHeader, $flagPdf, $aux)
    {
        $var      = '';
        $flagPdf  = $flagPdf;
        $retailer = $this->retailer;
        //edw-->$retailerProduct = $this->retailerProduct;
        $retailerProduct = RetailerProduct::where('ad_company_product_id', $aux)->get();

        $cli    = [ ];
        $cli    = \Sibas\Entities\De\Header::where('id', $idHeader)->first();
        $detail = Detail::where('id', $cli->details[0]->id)->first();

        $data = false;
        if ($cli->facultative == true && $cli->issued == false) {
            $data = $cli->facultative_observation;
        } elseif ($cli->facultative == true && $cli->issued == true) {
            $data = Facultative::where('op_de_detail_id', $detail->id)->get();
        }

        $header = Header::where('id', $idHeader)->first();

        switch ($type) {
            case 'cotizacion':
                $resQuestion = $this->getEvaluationResponse($detail->response);// no sirve
                $imc         = $detail->client->imc;// no sirve

                foreach ($cli->details as $key => $value) {
                    $cli->details[$key]->resQuestion = $this->getEvaluationResponse($value->response);
                }

                $var = [
                    'template_cert' => view('cert.cert_cotizacion',
                        compact('header', 'cli', 'idHeader', 'type', 'flagPdf', 'retailer', 'retailerProduct',
                            'resQuestion', 'imc', 'aux'))->render()
                ];
                break;
            case 'emision':

                $question = [ ];
                $i        = 1;
                foreach ($cli->details as $key => $value) {
                    foreach (json_decode($value->response->response) as $key2 => $value2) {
                        $question[$value2->question][$i] = $value2->response;
                    }
                    $i++;
                }

                $adRates = DB::table('ad_rates')->get();
                $adRates = $adRates[0];

                $var = [
                    'template_cert' => view('cert.cert_emision',
                        compact('header', 'cli', 'question', 'adRates', 'idHeader', 'type', 'flagPdf', 'retailer',
                            'retailerProduct', 'data', 'aux'))->render()
                ];
                break;
            case 'sub_vida_emision':

                $deDetail = \Sibas\Entities\De\Detail::where('op_de_header_id', $idHeader)->first();
                $viDetail = \Sibas\Entities\Vi\Detail::where('op_client_id', $deDetail->op_client_id)->first();
                $viHeader = \Sibas\Entities\Vi\Header::where('id', $viDetail->op_vi_header_id)->first();

                $cli = $viDetail;
                $var = [
                    'template_cert' => view('cert.cert_emision_vida',
                        compact('viDetail', 'viHeader', 'idHeader', 'type', 'flagPdf', 'retailer', 'retailerProduct',
                            'aux'))->render()
                ];
                break;

            case 'print_all':
                $question = [ ];
                $i        = 1;
                foreach ($cli->details as $key => $value) {
                    foreach (json_decode($value->response->response) as $key2 => $value2) {
                        $question[$value2->question][$i] = $value2->response;
                    }
                    $i++;
                }
                $adRates = DB::table('ad_rates')->get();
                $adRates = $adRates[0];

                # sub producto vida
                $deDetail = \Sibas\Entities\De\Detail::where('op_de_header_id', $idHeader)->first();

                $viDetail = 0;
                $flag     = 0;
                if (count($deDetail) > 0) {
                    $viDetail = \Sibas\Entities\Vi\Detail::where('op_client_id', $deDetail->op_client_id)->first();
                    $flag     = 1;
                }

                # validacion si existe sub.producto
                $viHeader = 0;
                if (count($viDetail) > 0) {
                    $viHeader = \Sibas\Entities\Vi\Header::where('id', $viDetail->op_vi_header_id)->first();
                }

                $var = [
                    'template_cert' => view('cert.cert_all',
                        compact('header', 'cli', 'question', 'adRates', 'viDetail', 'viHeader', 'flag', 'idHeader',
                            'type', 'flagPdf', 'retailer', 'retailerProduct', 'data', 'aux'))->render()
                ];

                break;
            default:
                break;
        }
        $arr = [ 'html' => $var, 'cli' => $cli ];

        return $arr;
    }


    public function getEvaluationResponse($response)
    {
        $questions = json_decode($response->response, true);

        foreach ($questions as $question) {
            if ($question['expected'] != $question['response']) {
                return true;
            }
        }

        return false;
    }

}
